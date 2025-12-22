<?php

namespace App\Controller\Capture;

use App\Entity\Account\Account;
use App\Entity\Capture\Capture;
use App\Form\Capture\CaptureElement\CaptureElementInternalForm;
use App\Form\Capture\CaptureNewForm;
use App\Repository\CaptureRepository;
use App\Service\ConditionToggler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/capture')]
final class CaptureController extends AbstractController
{
    #[Route(name: 'app_capture_index', methods: ['GET'])]
    public function index(CaptureRepository $captureRepository): Response
    {
        $all = $captureRepository->findAll();
        $templates = array_filter($all, fn ($el) => !$el->isTemplate());

        return $this->render('capture/index.html.twig', [
            'captures' => $templates,
        ]);
    }

    #[Route('/new', name: 'app_capture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CaptureNewForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Account $account */
            $account = $form->get('account')->getData();

            /** @var Capture $template */
            $template = $form->get('template')->getData();

            $name = (string) $form->get('name')->getData();
            $description = $form->get('description')->getData();

            $clone = $this->cloneCaptureFromTemplate($template, $account, $name, $description);

            $em->persist($clone);
            $em->flush();

            return $this->redirectToRoute('app_capture_edit', ['id' => $clone->getId()]);
        }

        return $this->render('capture/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_capture_show', methods: ['GET'])]
    public function show(Capture $capture): Response
    {
        return $this->render('capture/show.html.twig', [
            'capture' => $capture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_capture_edit', methods: ['GET'])]
    public function edit(Capture $capture, ConditionToggler $toggler): Response
    {
        // apply toggle activation from conditions
        $conditions = $capture->getConditions();
        $toggler->apply(is_iterable($conditions) ? $conditions : []);

        // map conditions by target element id for displaying
        $conditionsByTargetId = [];
        foreach ($capture->getConditions() as $cond) {
            $tid = $cond->getTargetElement()?->getId();
            if (null !== $tid) {
                $conditionsByTargetId[$tid][] = $cond;
            }
        }

        // build one form per element
        $elementForms = [];
        foreach ($capture->getCaptureElements() as $element) {
            $elementForms[$element->getId()] = $this->createForm(
                CaptureElementInternalForm::class,
                $element,
                [
                    'action' => $this->generateUrl('app_capture_element_respond', [
                        'id' => $element->getId(),
                        'captureId' => $capture->getId(),
                    ]),
                    'method' => 'POST',
                    'disabled' => !$element->isActive(),
                ]
            )->createView();
        }


        return $this->render('capture/edit.html.twig', [
            'capture' => $capture,
            'elementForms' => $elementForms,
            'conditionsByTargetId' => $conditionsByTargetId,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_capture_delete', methods: ['POST'])]
    public function delete(Request $request, Capture $capture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$capture->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($capture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_capture_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/render-preview', name: 'app_capture_render_text_preview', methods: ['GET'])]
    public function renderPreview(Capture $capture): Response
    {
        return $this->render('capture/render_preview.html.twig', [
            'capture' => $capture,
        ]);
    }

    private function cloneCaptureFromTemplate(Capture $template, Account $account, ?string $name, ?string $description): Capture
    {
        if (!$template->isTemplate()) {
            throw new NotFoundHttpException('Cette capture n’est pas un template.');
        }

        $clone = clone $template;

        $clone->setTemplate(false);
        $clone->setAccount($account);

        if (null !== $name && '' !== trim($name)) {
            $clone->setName($name);
        }

        if (null !== $description && '' !== trim($description)) {
            $clone->setDescription($description);
        }

        return $clone;
    }
}
