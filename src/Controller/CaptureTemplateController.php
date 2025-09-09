<?php

namespace App\Controller;

use App\Entity\Capture;
use App\Entity\CaptureElement;
use App\Form\Capture\CaptureTemplateForm;
use App\Repository\CaptureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/capture-template')]
final class CaptureTemplateController extends AbstractController
{
    #[Route(name: 'app_capture_template_index', methods: ['GET'])]
    public function index(CaptureRepository $captureRepository): Response
    {
        $all = $captureRepository->findAll();
        $templates = array_filter($all, fn($el) => $el->isTemplate());

        return $this->render('capture_template/index.html.twig', [
            'captures' => $templates,
        ]);
    }

    #[Route('/new', name: 'app_capture_template_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $capture = new Capture();
        $form = $this->createForm(CaptureTemplateForm::class, $capture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($capture);
            $entityManager->flush();

            return $this->redirectToRoute('app_capture_template_edit', ['id' => $capture->getId(),], Response::HTTP_SEE_OTHER);
        }

        return $this->render('capture_template/new.html.twig', [
            'capture' => $capture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_capture_template_show', methods: ['GET'])]
    public function show(Capture $capture): Response
    {
        return $this->render('capture_template/show.html.twig', [
            'capture' => $capture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_capture_template_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Capture $capture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CaptureTemplateForm::class, $capture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }

        return $this->render('capture_template/edit.html.twig', [
            'capture' => $capture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_capture_template_delete', methods: ['POST'])]
    public function delete(Request $request, Capture $capture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$capture->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($capture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_capture_template_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/select',name: 'app_capture_template_select', methods: ['GET'])]
    public function select(Request $request, EntityManagerInterface $em): Response
    {
        $all = $em->getRepository(Capture::class)->findAll();
        $templates = array_filter($all, fn($el) => $el->isTemplate());

        return $this->render('capture_template/select.html.twig', [
            'captures' => $templates,
        ]);
    }
    #[Route('/{captureId}/elements/{id}/attach', name: 'app_capture_template_attach_element', methods: ['GET'])]
    public function attachElement(#[MapEntity(id: 'captureId')] Capture $capture, CaptureElement $element, EntityManagerInterface $em): Response
    {
        dump($capture);
        dump($element);
        $capture->addCaptureElement($element);
        dump($capture->getCaptureElements());
        $em->flush();

        return $this->redirectToRoute('app_capture_template_edit', ['id' => $capture->getId()]);
    }

    #[Route('/{id}/template-preview', name: 'app_capture_template_render_text_preview', methods: ['GET'])]
    public function templatePreview(Capture $capture): Response
    {
        return $this->render('capture_template/render_preview.html.twig', [
            'capture' => $capture,
        ]);
    }
}
