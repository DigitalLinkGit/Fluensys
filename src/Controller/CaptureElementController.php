<?php

namespace App\Controller;

use App\Entity\Capture;
use App\Entity\CaptureElement;
use App\Entity\FlexCaptureElement;
use App\Entity\Rendering\TextChapter;
use App\Form\CaptureElement\CaptureElementExternalForm;
use App\Form\CaptureElement\CaptureElementInternalForm;
use App\Form\Rendering\RenderTextEditorForm;
use App\Service\Rendering\TemplateInterpolator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/capture-element')]
final class CaptureElementController extends AbstractController
{

    #[Route(name: 'app_capture_element_template_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $all = $em->getRepository(CaptureElement::class)->findAll();
        $templates = array_filter($all, fn($el) => $el->isTemplate());

        return $this->render('capture_element/index.html.twig', [
            'capture_elements' => $templates,
        ]);
    }

    #[Route('/{id}/show', name: 'app_capture_element_show', methods: ['GET'])]
    public function show(CaptureElement $captureElement): Response
    {
        return $this->render('capture_element/show.html.twig', [
            'capture_element' => $captureElement,
        ]);
    }

    #[Route('/select',name: 'app_capture_element_select', methods: ['GET'])]
    public function select(Request $request, EntityManagerInterface $em): Response
    {
        $captureId = $request->query->getInt('capture', $request->query->getInt('capture'));
        $capture   = $em->getRepository(Capture::class)->find($captureId);

        $all = $em->getRepository(CaptureElement::class)->findAll();
        $already = $capture ? $capture->getCaptureElements() : new ArrayCollection();

        $alreadyIds = array_map(fn($e) => $e->getId(), $already->toArray());
        $available = array_filter($all, fn($el) => $el->isTemplate() && !in_array($el->getId(), $alreadyIds, true));

        return $this->render('capture_element/select.html.twig', [
            'capture_elements' => $available,
            'capture_id' => $captureId,
        ]);
    }

    #[Route('/{id}/external-preview', name: 'app_capture_element_external_preview', methods: ['GET'])]
    public function externalPreview(FlexCaptureElement $flexCapture): Response
    {
        $form = $this->createForm(CaptureElementExternalForm::class, $flexCapture);
        $title = 'Aperçu externe : ' . $flexCapture->getName();
        return $this->render('capture_element/preview.html.twig', [
            'flex_capture' => $flexCapture,
            'form'=>$form,
            'title'=>$title,
        ]);
    }

    #[Route('/{id}/internal-preview', name: 'app_capture_element_internal_preview', methods: ['GET'])]
    public function internalPreview(FlexCaptureElement $flexCapture): Response
    {
        $form = $this->createForm(CaptureElementInternalForm::class, $flexCapture);
        $title = 'Aperçu interne : ' . $flexCapture->getName();
        return $this->render('capture_element/preview.html.twig', [
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
            // Normalize placeholders to Twig
            $normalized = $interpolator->normalizeToTwig($chapter->getTemplateContent());
            // TODO: Persist the normalized template
            $flexCapture->setChapter($chapter);
            $entityManager->persist($chapter);
            $entityManager->flush();
            $this->addFlash('success', 'Rendu texte enregistré.');
            return $this->redirectToRoute('app_flex_capture_element_edit', ['id' => $flexCapture->getId()]);
        }

        return $this->render('capture_element/render_text_editor.html.twig', [
            'form' => $form,
            'variables' => $variables,
        ]);
    }
}
