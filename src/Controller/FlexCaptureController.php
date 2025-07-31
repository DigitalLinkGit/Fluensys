<?php

namespace App\Controller;

use App\Entity\FlexCapture;
use App\Form\FlexCaptureForm;
use App\Repository\FlexCaptureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/flex-capture')]
final class FlexCaptureController extends AbstractController
{
    #[Route(name: 'app_flex_capture_index', methods: ['GET'])]
    public function index(FlexCaptureRepository $flexCaptureRepository): Response
    {
        return $this->render('flex_capture/index.html.twig', [
            'flex_captures' => $flexCaptureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_flex_capture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $flexCapture = new FlexCapture();
        $form = $this->createForm(FlexCaptureForm::class, $flexCapture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($flexCapture);
            $entityManager->flush();

            return $this->redirectToRoute('app_flex_capture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('flex_capture/new.html.twig', [
            'flex_capture' => $flexCapture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_flex_capture_show', methods: ['GET'])]
    public function show(FlexCapture $flexCapture): Response
    {
        return $this->render('flex_capture/show.html.twig', [
            'flex_capture' => $flexCapture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_flex_capture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FlexCapture $flexCapture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FlexCaptureForm::class, $flexCapture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_flex_capture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('flex_capture/edit.html.twig', [
            'flex_capture' => $flexCapture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_flex_capture_delete', methods: ['POST'])]
    public function delete(Request $request, FlexCapture $flexCapture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$flexCapture->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($flexCapture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_flex_capture_index', [], Response::HTTP_SEE_OTHER);
    }
}
