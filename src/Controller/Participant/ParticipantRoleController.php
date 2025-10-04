<?php

namespace App\Controller\Participant;

use App\Entity\Participant\ParticipantRole;
use App\Form\Participant\ParticipantRoleForm;
use App\Repository\ParticipantRoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/participant-role')]
final class ParticipantRoleController extends AbstractController
{
    #[Route(name: 'app_participant_role_index', methods: ['GET'])]
    public function index(ParticipantRoleRepository $participantRoleRepository): Response
    {
        return $this->render('participant_role/index.html.twig', [
            'participant_roles' => $participantRoleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_participant_role_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $participantRole = new ParticipantRole();
        $form = $this->createForm(ParticipantRoleForm::class, $participantRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participantRole);
            $entityManager->flush();

            return $this->redirectToRoute('app_participant_role_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('participant_role/new.html.twig', [
            'participant_role' => $participantRole,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participant_role_show', methods: ['GET'])]
    public function show(ParticipantRole $participantRole): Response
    {
        return $this->render('participant_role/show.html.twig', [
            'participant_role' => $participantRole,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_participant_role_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ParticipantRole $participantRole, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipantRoleForm::class, $participantRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_participant_role_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('participant_role/edit.html.twig', [
            'participant_role' => $participantRole,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_participant_role_delete', methods: ['POST'])]
    public function delete(Request $request, ParticipantRole $participantRole, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participantRole->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($participantRole);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_participant_role_index', [], Response::HTTP_SEE_OTHER);
    }
}
