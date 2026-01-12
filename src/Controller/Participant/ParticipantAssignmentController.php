<?php

namespace App\Controller\Participant;

use App\Entity\Capture\Capture;
use App\Entity\Interface\LivecycleStatusAwareInterface;
use App\Entity\Project;
use App\Entity\Tenant\User;
use App\Form\Participant\ParticipantAssignmentForm;
use App\Service\Helper\LivecycleStatusManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class ParticipantAssignmentController extends AbstractController
{
    public function __construct(
        private readonly LivecycleStatusManager $statusManager,
    ) {
    }

    #[Route('/capture/{id}/assign', name: 'app_capture_participant_assign', methods: ['GET', 'POST'])]
    public function assignForCapture(Request $request, Capture $capture, EntityManagerInterface $em): Response
    {
        return $this->handleAssign($request, $capture, $em);
    }

    #[Route('/project/{id}/assign', name: 'app_project_participant_assign', methods: ['GET', 'POST'])]
    public function assignForProject(Request $request, Project $project, EntityManagerInterface $em): Response
    {
        return $this->handleAssign($request, $project, $em);
    }

    private function handleAssign(Request $request, LivecycleStatusAwareInterface $projectOrCapture, EntityManagerInterface $em): Response
    {
        if ($projectOrCapture->isTemplate()) {
            throw new NotFoundHttpException('Assignment is not available on templates.');
        }
        $project = null;
        $capture = null;

        if ($projectOrCapture instanceof Project) {
            $project = $projectOrCapture;
        } else {
            $capture = $projectOrCapture; // Capture
        }

        $formAction = $projectOrCapture instanceof Project
            ? $this->generateUrl('app_project_participant_assign', ['id' => $projectOrCapture->getId()])
            : $this->generateUrl('app_capture_participant_assign', ['id' => $projectOrCapture->getId()]);

        $form = $this->createForm(ParticipantAssignmentForm::class, $projectOrCapture, [
            'action' => $formAction,
            'method' => 'POST',
            'data_class' => \get_class($projectOrCapture),
        ]);

        $form->handleRequest($request);

        /** @var User|null $user */
        $user = $this->getUser();

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->statusManager->refresh($projectOrCapture, $user, false);

                $em->persist($projectOrCapture);

                foreach ($projectOrCapture->getParticipantAssignments() as $a) {
                    $em->persist($a);
                }
                $em->flush();

                if ($projectOrCapture instanceof Project) {
                    return $this->redirectToRoute('app_project_edit', ['id' => $projectOrCapture->getId()]);
                }

                return $this->redirectToRoute('app_capture_edit', ['id' => $projectOrCapture->getId()]);
            }

            foreach ($form->getErrors(true, true) as $error) {
                $this->addFlash('danger', $error->getMessage());
            }
        }

        return $this->render('participant_role/participant_assignment_form.html.twig', [
            'id' => $projectOrCapture->getId(),
            'form' => $form,
            'capture' => $capture,
            'project' => $project,
        ]);
    }
}
