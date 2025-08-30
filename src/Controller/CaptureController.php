<?php

namespace App\Controller;

use App\Entity\Capture;
use App\Entity\CaptureElement;
use App\Entity\Project;
use App\Form\Capture\CaptureForm;
use App\Repository\CaptureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
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
        return $this->render('capture/index.html.twig', [
            'captures' => $captureRepository->findAll(),
        ]);
    }

    #[Route('/select',name: 'app_capture_select', methods: ['GET'])]
    public function select(Request $request, EntityManagerInterface $em): Response
    {
        $projectId = $request->query->getInt('project');
        $project   = $em->getRepository(Project::class)->find($projectId);

        $all = $em->getRepository(Capture::class)->findAll();
        $already = $project ? $project->getCaptures() : new ArrayCollection();

        $alreadyIds = array_map(fn($e) => $e->getId(), $already->toArray());
        $available = array_filter($all, fn($el) => $el->isTemplate() && !in_array($el->getId(), $alreadyIds, true));

        return $this->render('capture/select.html.twig', [
            'captures' => $available,
            'project_id' => $projectId,
        ]);
    }

    #[Route('/new', name: 'app_capture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $capture = new Capture();
        $form = $this->createForm(CaptureForm::class, $capture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($capture);
            $entityManager->flush();

            return $this->redirectToRoute('app_capture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('capture/new.html.twig', [
            'capture' => $capture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_capture_show', methods: ['GET'])]
    public function show(Capture $capture): Response
    {
        return $this->render('capture/show.html.twig', [
            'capture' => $capture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_capture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Capture $capture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CaptureForm::class, $capture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_capture_template_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('capture/edit.html.twig', [
            'capture' => $capture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_capture_delete', methods: ['POST'])]
    public function delete(Request $request, Capture $capture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$capture->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($capture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_capture_template_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{captureId}/elements/{id}/attach', name: 'app_capture_attach_element', methods: ['GET'])]
    public function attachElement(#[MapEntity(id: 'captureId')] Capture $capture, CaptureElement $element, EntityManagerInterface $em): Response
    {
        $capture->addCaptureElement($element);
        $em->flush();

        return $this->redirectToRoute('app_capture_edit', ['id' => $capture->getId()]);
    }

}
