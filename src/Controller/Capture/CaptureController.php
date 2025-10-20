<?php

namespace App\Controller\Capture;

use App\Entity\Capture\Capture;
use App\Form\Capture\CaptureElement\CaptureElementInternalForm;
use App\Form\Capture\CaptureInternalForm;
use App\Repository\CaptureRepository;
use App\Service\ConditionToggler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $capture = new Capture();
        $form = $this->createForm(CaptureInternalForm::class, $capture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($capture);
            $entityManager->flush();

            return $this->redirectToRoute('app_capture_edit', ['id' => $capture->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('capture/new.html.twig', [
            'capture' => $capture,
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

    #[Route('/{id}/edit', name: 'app_capture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Capture $capture, EntityManagerInterface $entityManager, ConditionToggler $toggler): Response
    {

        $form = $this->createForm(CaptureInternalForm::class, $capture);
        $form->handleRequest($request);

        // apply toggle activation from conditions
        $conditions = $capture->getConditions();
        $toggler->apply(is_iterable($conditions) ? $conditions : []);

        // make condition map for display condition on CaptureElement
        $conditionsByTargetId = [];
        foreach ($capture->getConditions() as $cond) {
            $tid = $cond->getTargetElement()?->getId();
            if (null !== $tid) {
                $conditionsByTargetId[$tid][] = $cond;
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $toggler->apply($capture->getConditions()->toArray());
            $entityManager->flush();
        }

        return $this->render('capture/edit.html.twig', [
            'capture' => $capture,
            'form' => $form,
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

    #[Route('/capture/{id}/clone', name: 'app_capture_clone', methods: ['GET'])]
    public function cloneFromTemplate(Capture $template, EntityManagerInterface $em): Response
    {
        if (!$template->isTemplate()) {
            throw $this->createNotFoundException('Cette capture n’est pas un template.');
        }

        $clone = clone $template;

        $clone->setTemplate(false);

        if (method_exists($clone, 'getName') && method_exists($clone, 'setName')) {
            $base = $clone->getName() ?: 'Capture';
            $clone->setName($base.' (copie)');
        }

        $em->persist($clone);
        $em->flush();

        return $this->redirectToRoute('app_capture_edit', ['id' => $clone->getId()]);
    }
}
