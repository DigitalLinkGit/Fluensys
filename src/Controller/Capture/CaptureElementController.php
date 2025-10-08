<?php

namespace App\Controller\Capture;

use App\Controller\AbstractAppController;
use App\Entity\Capture\CaptureTemplate;
use App\Entity\Capture\CaptureElement\CaptureElement;
use App\Entity\Capture\CaptureElement\FlexCaptureElement;
use App\Entity\Capture\Rendering\TextChapter;
use App\Form\Capture\CaptureElement\CaptureElementExternalForm;
use App\Form\Capture\CaptureElement\CaptureElementInternalForm;
use App\Form\Capture\CaptureElement\CaptureElementNewForm;
use App\Form\Capture\Rendering\RenderTextEditorForm;
use App\Repository\CaptureRepository;
use App\Service\CaptureElementRouter;
use App\Service\Factory\CaptureElementFactory;
use App\Service\Helper\CaptureElementTypeHelper;
use App\Service\Rendering\TemplateInterpolator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/capture-element')]
final class CaptureElementController extends AbstractAppController
{

    #[Route(name: 'app_capture_element_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $all = $em->getRepository(CaptureElement::class)->findAll();
        $templates = array_filter($all, fn($el) => $el->isTemplate());

        return $this->render('capture/capture_element/index.html.twig', [
            'capture_elements' => $templates,
        ]);
    }

    #[Route('/{id}/show', name: 'app_capture_element_show', methods: ['GET'])]
    public function show(CaptureElement $captureElement): Response
    {
        return $this->render('capture/capture_element/show.html.twig', [
            'capture_element' => $captureElement,
        ]);
    }

    #[Route('/select',name: 'app_capture_element_select', methods: ['GET'])]
    public function select(Request $request, EntityManagerInterface $em): Response
    {
        $captureId = $request->query->getInt('capture', $request->query->getInt('capture'));
        $capture   = $em->getRepository(CaptureTemplate::class)->find($captureId);

        $all = $em->getRepository(CaptureElement::class)->findAll();
        $already = $capture ? $capture->getCaptureElements() : new ArrayCollection();

        $alreadyIds = array_map(fn($e) => $e->getId(), $already->toArray());
        $available = array_filter($all, fn($el) => $el->isTemplate() && !in_array($el->getId(), $alreadyIds, true));

        return $this->render('capture/capture_element/select.html.twig', [
            'capture_elements' => $available,
            'capture_id' => $captureId,
        ]);
    }

    #[Route('/{id}/external-preview', name: 'app_capture_element_external_preview', methods: ['GET'])]
    public function externalPreview(FlexCaptureElement $flexCapture): Response
    {
        $form = $this->createForm(CaptureElementExternalForm::class, $flexCapture);
        $title = 'Aperçu externe : ' . $flexCapture->getName();
        return $this->render('capture/capture_element/preview.html.twig', [
            'flex_capture' => $flexCapture,
            'form'=>$form,
            'title'=>$title,
        ]);
    }

    #[Route('/{id}/internal-preview', name: 'app_capture_element_internal_preview', methods: ['GET'])]
    public function internalPreview(FlexCaptureElement $flexCapture): Response
    {
        $form = $this->createForm(CaptureElementInternalForm::class, $flexCapture);
        //dd(array_map(fn($f) => get_class($f), $flexCapture->getFields()->toArray()));
        $title = 'Aperçu interne : ' . $flexCapture->getName();
        return $this->render('capture/capture_element/preview.html.twig', [
            'flex_capture' => $flexCapture,
            'form'=>$form,
            'title'=>$title,
        ]);
    }

    #[Route('/{id}/render-text/edit', name: 'app_capture_element_render_text_edit', methods: ['GET','POST'])]
    public function editRenderText(Request $request, FlexCaptureElement $flexCapture, TemplateInterpolator $interpolator, EntityManagerInterface $entityManager): Response
    {
        // Build available variables from Fields and CalculatedVariables
        $fieldVars = [];
        foreach ($flexCapture->getFields() as $f) {
            $name = $f->getTechnicalName();
            if ($name) { $fieldVars[] = $name; }
        }
        $calcVars = [];
        foreach ($flexCapture->getCalculatedvariables() as $cv) {
            $name = $cv->getTechnicalName();
            if ($name) { $calcVars[] = $name; }
        }
        $variables = array_values(array_unique(array_merge($fieldVars, $calcVars)));

        $chapter = $flexCapture->getChapter() ?? new TextChapter();
        $form = $this->createForm(RenderTextEditorForm::class, $chapter, [
            'variables' => $variables,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $flexCapture->setChapter($chapter);
            $entityManager->persist($chapter);
            $entityManager->flush();
            $this->addFlash('success', 'Rendu texte enregistré.');
            return $this->redirectToRoute('app_flex_capture_element_edit', ['id' => $flexCapture->getId()]);
        }

        return $this->render('capture/capture_element/render_text_editor.html.twig', [
            'form' => $form,
            'variables' => $variables,
        ]);
    }

    #[Route('/{id}/respond/{captureId}', name: 'app_capture_element_respond', methods: ['POST'])]
    public function save(CaptureElement $el, int $captureId, Request $r, EntityManagerInterface $em, CaptureRepository $captureRepo): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $capture = $captureRepo->find($captureId) ?? throw $this->createNotFoundException();

        $form = $this->createForm(CaptureElementInternalForm::class, $el);
        $form->handleRequest($r);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();
        }

        return $this->redirectToRoute('app_capture_edit', [
            'id' => $captureId,
            '_fragment' => 'el-'.$el->getId(),
            'capture'=>$capture,
        ]);
    }

    #[Route('/new', name: 'app_capture_element_new', methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $em, CaptureElementTypeHelper $typeHelper,CaptureElementFactory $factory, CaptureElementRouter $router): Response {
        $form = $this->createForm(CaptureElementNewForm::class, null, [
            'type_choices' => $typeHelper->getFormChoices(), // [label => key]
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data    = $form->getData();
            $typeKey = $form->get('type')->getData();

            $element = $factory->createFromForm($typeKey, $data);

            $em->persist($element);
            $em->flush();

            [$route, $params] = $router->resolveEditRoute($element);
            return $this->redirectToRoute($route, $params);
        }

        return $this->render('capture/capture_element/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_capture_element_delete', methods: ['POST'])]
    public function delete(Request $request, CaptureElement $element, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete' . $element->getId(), $request->getPayload()->getString('_token'))) {
            $this->addFlash('danger', 'Jeton CSRF invalide. Suppression annulée.');
            return $this->redirectToRoute('app_flex_capture_element_index', [], Response::HTTP_SEE_OTHER);
        }

        try {
            $entityManager->remove($element);
            $entityManager->flush();

            $this->addFlash('success', 'L’élément a bien été supprimé.');
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->addFlash('warning', 'Impossible de supprimer cet élément car il est utilisé dans au moins une capture.');
        } catch (\Throwable $e) {
            $this->logger?->error('Erreur lors de la suppression', ['id' => $element->getId(), 'exception' => $e]);
            $this->addFlash('danger', 'Une erreur inattendue est survenue pendant la suppression.');
        }

        return $this->redirectToRoute('app_capture_element_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_capture_element_edit', methods: ['GET','POST'])]
    public function edit(CaptureElement $element, CaptureElementRouter $router): Response
    {
        [$route, $params] = $router->resolveEditRoute($element);
        return $this->redirectToRoute($route, $params);
    }
}
