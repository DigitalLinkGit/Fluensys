<?php

namespace App\Controller;

use App\Entity\Account\Account;
use App\Entity\Capture\Capture;
use App\Entity\Enum\ActivityAction;
use App\Entity\Enum\LivecycleStatus;
use App\Entity\Project;
use App\Entity\Tenant\User;
use App\Form\ProjectContributorNewForm;
use App\Form\ProjectTemplateForm;
use App\Repository\CaptureRepository;
use App\Repository\ProjectRepository;
use App\Service\Helper\ActivityLogLogger;
use App\Service\Helper\ActivityLogProvider;
use App\Service\Helper\LivecycleStatusManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/project')]
final class ProjectController extends AbstractController
{
    public function __construct(
        private readonly LivecycleStatusManager $statusManager,
        private readonly ActivityLogProvider $activityLogProvider,
        private readonly ActivityLogLogger $activityLogLogger,
    ) {
    }

    #[Route(name: 'app_project_index', methods: ['GET'])]
    #[Route('/templates', name: 'app_project_template_index', methods: ['GET'])]
    public function index(Request $request, ProjectRepository $projectRepository): Response
    {
        $isTemplateIndex = 'app_project_template_index' === $request->attributes->get('_route');

        $all = $projectRepository->findAll();

        $projects = array_filter($all, function (Project $el) use ($isTemplateIndex) {
            if ($isTemplateIndex) {
                return \in_array($el->getStatus(), [LivecycleStatus::TEMPLATE, LivecycleStatus::DRAFT], true);
            }

            return !\in_array($el->getStatus(), [LivecycleStatus::TEMPLATE, LivecycleStatus::DRAFT], true);
        });

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
            'templateMode' => $isTemplateIndex,
        ]);
    }

    #[Route('/template/new', name: 'app_project_template_new', methods: ['GET', 'POST'])]
    public function newTemplate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectTemplateForm::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();
            $this->activityLogLogger->logForProject($project, ActivityAction::TEMPLATE_CREATED, $this->getUser());
            return $this->redirectToRoute('app_project_template_edit', ['id' => $project->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form,
            'templateMode' => true,
        ]);
    }

    #[Route('/new', name: 'app_project_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectContributorNewForm::class);
        $form->handleRequest($request);
        /** @var User|null $user */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Account $account */
            $account = $form->get('account')->getData();
            /** @var Project $template */
            $template = $form->get('template')->getData();
            $name = (string) $form->get('name')->getData();
            $description = $form->get('description')->getData();

            $clone = $this->cloneProjectFromTemplate($template, $account, $name, $description);
            $clone->setResponsible($user);
            foreach ($clone->getCaptures() as $capture) {
                $capture->setResponsible($user);
                $capture->setAccount($account);
            }
            $this->statusManager->init($clone, $user, false);
            $entityManager->persist($clone);
            $entityManager->flush();
            $this->activityLogLogger->logForProject($clone, ActivityAction::CREATED, $this->getUser());
            return $this->redirectToRoute('app_project_participant_assign', ['id' => $clone->getId()]);
        }

        return $this->render('project/new.html.twig', [
            'form' => $form,
            'templateMode' => false,
        ]);
    }

    #[Route('/{id}', name: 'app_project_show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectTemplateForm::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->activityLogLogger->logForProject($project, ActivityAction::UPDATED, $this->getUser());
            return $this->redirectToRoute('app_project_edit', [
                'id' => $project->getId(),
            ], Response::HTTP_SEE_OTHER);
        }
        $activity_logs = $this->activityLogProvider->forProject($project);
        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
            'templateMode' => false,
            'activity_logs' => $activity_logs,
        ]);
    }

    #[Route('/template/{id}/edit', name: 'app_project_template_edit', methods: ['GET', 'POST'])]
    public function editTemplate(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectTemplateForm::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->activityLogLogger->logForProject($project, ActivityAction::TEMPLATE_UPDATED, $this->getUser());
            return $this->redirectToRoute('app_project_template_edit', [
                'id' => $project->getId(),
            ], Response::HTTP_SEE_OTHER);
        }
        $activity_logs = $this->activityLogProvider->forProject($project);
        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
            'templateMode' => true,
            'activity_logs' => $activity_logs,
        ]);
    }

    #[Route('/{id}', name: 'app_project_delete', methods: ['POST'])]
    public function delete(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($project);
            $entityManager->flush();
            $this->activityLogLogger->logForProject($project, ActivityAction::DELETED, $this->getUser());
        }

        return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{projectId}/capture/{id}/add/{kind}', name: 'app_project_template_add_capture', methods: ['GET'])]
    public function addCapture(#[MapEntity(id: 'projectId')] Project $project, Capture $capture, string $kind, EntityManagerInterface $em): Response
    {
        if ('recurring' === $kind) {
            $project->addRecurringCapturesTemplates($capture);
        } elseif ('standard' === $kind) {
            $project->addCapture($capture);
        } else {
            throw new BadRequestHttpException('Invalid kind. Expected "standard" or "recurring".');
        }
        $em->flush();
        $this->activityLogLogger->logForProject($project, ActivityAction::TEMPLATE_UPDATED, $this->getUser());
        return $this->redirectToRoute('app_project_edit', ['id' => $project->getId()]);
    }

    #[Route('/{projectId}/capture/{id}/remove/{kind}', name: 'app_project_template_delete_capture', methods: ['GET', 'POST'])]
    public function removeCapture(#[MapEntity(id: 'projectId')] Project $project, Capture $capture, string $kind, EntityManagerInterface $em): Response
    {
        if ('recurring' === $kind) {
            $project->removeRecurringCapturesTemplates($capture);
        } elseif ('standard' === $kind) {
            $project->removeCapture($capture);
        } else {
            throw new BadRequestHttpException('Invalid kind. Expected "standard" or "recurring".');
        }
        $em->flush();
        $this->activityLogLogger->logForProject($project, ActivityAction::TEMPLATE_UPDATED, $this->getUser());
        return $this->redirectToRoute('app_project_edit', ['id' => $project->getId()]);
    }

    #[Route('/{projectId}/capture/select/{kind}', name: 'app_project_template_select_capture', methods: ['GET'])]
    public function selectCapture(int $projectId, string $kind, CaptureRepository $captureRepository): Response
    {
        if (!\in_array($kind, ['standard', 'recurring'], true)) {
            throw $this->createNotFoundException();
        }
        $all = $captureRepository->findAll();
        $captures = array_filter($all, function (Capture $el) {
            return \in_array($el->getStatus(), [LivecycleStatus::TEMPLATE], true);
        });

        return $this->render('project/select_capture_template.html.twig', [
            'projectId' => $projectId,
            'kind' => $kind,
            'captures' => $captures,
        ]);
    }

    #[Route('/template/{id}/publish', name: 'app_project_template_publish', methods: ['GET'])]
    public function publishTemplate(Project $project, EntityManagerInterface $em): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if (!$project->isDraft()) {
            throw new NotFoundHttpException('Ce template de projet ne peux pas être publiée');
        }
        // ToDo: Validation before publish
        $this->statusManager->publishTemplate($project, $user);
        $em->flush();
        $this->activityLogLogger->logForProject($project, ActivityAction::PUBLISHED, $this->getUser());
        return $this->redirectToRoute('app_project_edit', ['id' => $project->getId()]);
    }

    #[Route('/template/{id}/unpublish', name: 'app_project_template_unpublish', methods: ['GET'])]
    public function unpublishTemplate(Project $project, EntityManagerInterface $em): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        // ToDo: Validation before publish
        $this->statusManager->unpublishTemplate($project, $user);
        $em->flush();
        $this->activityLogLogger->logForProject($project, ActivityAction::UNPUBLISHED, $this->getUser());
        return $this->redirectToRoute('app_project_edit', ['id' => $project->getId()]);
    }

    #[Route('/template/{id}/start', name: 'app_project_start', methods: ['GET'])]
    public function start(Project $project, EntityManagerInterface $em): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if (!$project->isInPreparation()) {
            throw new NotFoundHttpException('Ce projet ne peut pas être lancé');
        }
        // ToDo: Validation before starting
        $this->statusManager->start($project, $user);
        $em->flush();
        $this->activityLogLogger->logForProject($project, ActivityAction::STARTED, $this->getUser());
        return $this->redirectToRoute('app_project_edit', ['id' => $project->getId()]);
    }
    private function cloneProjectFromTemplate(Project $template, Account $account, string $name, mixed $description): Project
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if (!$template->isTemplate()) {
            throw new NotFoundHttpException('Ce projet n’est pas un template.');
        }
        $clone = clone $template;
        $clone->setAccount($account);
        $clone->setResponsible($user);
        foreach ($clone->getCaptures() as $capture) {
            $capture->setAccount($account);
            $capture->setResponsible($user);
            $capture->setOwnerProject($clone);
        }
        if (null !== $name && '' !== trim($name)) {
            $clone->setName($name);
        }
        if (null !== $description && '' !== trim($description)) {
            $clone->setDescription($description);
        }

        return $clone;
    }
}
