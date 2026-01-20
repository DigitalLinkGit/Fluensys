<?php

namespace App\Controller\Capture;

use App\Entity\Account\Account;
use App\Entity\Capture\Capture;
use App\Entity\Capture\CaptureElement;
use App\Entity\Enum\ActivityAction;
use App\Entity\Enum\LivecycleStatus;
use App\Entity\Project;
use App\Entity\Tenant\User;
use App\Form\Capture\CaptureContributorForm;
use App\Form\Capture\CaptureContributorNewForm;
use App\Form\Capture\CaptureElement\CaptureElementContributorForm;
use App\Form\Capture\CaptureTemplateForm;
use App\Form\Capture\CaptureTemplateNewForm;
use App\Repository\CaptureRepository;
use App\Service\Helper\ActivityLogLogger;
use App\Service\Helper\ActivityLogProvider;
use App\Service\Helper\ConditionToggler;
use App\Service\Helper\LivecycleStatusManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/capture')]
final class CaptureController extends AbstractController
{
    public function __construct(
        private readonly LivecycleStatusManager $statusManager,
        private readonly ActivityLogProvider $activityLogProvider,
        private readonly ActivityLogLogger $activityLogLogger,
    ) {
    }

    #[Route(name: 'app_capture_index', methods: ['GET'])]
    #[Route('/templates', name: 'app_capture_template_index', methods: ['GET'])]
    public function index(Request $request, CaptureRepository $captureRepository): Response
    {
        $isTemplateIndex = 'app_capture_template_index' === $request->attributes->get('_route');

        $all = $captureRepository->findAll();

        $captures = array_filter($all, function (Capture $el) use ($isTemplateIndex) {
            if ($isTemplateIndex) {
                return \in_array($el->getStatus(), [LivecycleStatus::TEMPLATE, LivecycleStatus::DRAFT], true);
            }

            return !\in_array($el->getStatus(), [LivecycleStatus::TEMPLATE, LivecycleStatus::DRAFT], true);
        });

        return $this->render('capture/index.html.twig', [
            'captures' => $captures,
            'templateMode' => $isTemplateIndex,
        ]);
    }

    #[Route('/new', name: 'app_capture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        $form = $this->createForm(CaptureContributorNewForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Account $account */
            $account = $form->get('account')->getData();
            /** @var Capture $template */
            $template = $form->get('template')->getData();
            $name = (string) $form->get('name')->getData();
            $description = $form->get('description')->getData();

            $clone = $this->cloneCaptureFromTemplate($template, $account, $name, $description);
            $clone->setResponsible($user);

            $em->persist($clone);
            $em->flush();
            $this->activityLogLogger->logForCapture($clone, ActivityAction::CREATED, $user);

            return $this->redirectToRoute('app_capture_participant_assign', [
                'id' => $clone->getId(),
            ]);
        }

        return $this->render('capture/new.html.twig', [
            'form' => $form,
            'templateMode' => false,
        ]);
    }

    #[Route('/new/recurring', name: 'app_capture_recurring_new', methods: ['GET', 'POST'])]
    public function newRecurringCapture(Request $request, EntityManagerInterface $em): Response
    {
        $accountId = $request->query->getInt('accountId');
        $templateId = $request->query->getInt('templateId');
        $projectId = $request->query->getInt('projectId');

        /** @var User|null $user */
        $user = $this->getUser();

        /** @var Account|null $account */
        $account = $em->getRepository(Account::class)->find($accountId);
        if (!$account) {
            throw $this->createNotFoundException('Account not found.');
        }

        /** @var Capture|null $template */
        $template = $em->getRepository(Capture::class)->find($templateId);
        if (!$template) {
            throw $this->createNotFoundException('Template not found.');
        }

        /** @var Project|null $project */
        $project = $em->getRepository(Project::class)->find($projectId);
        if (!$project) {
            throw $this->createNotFoundException('project not found.');
        }

        $clone = $this->cloneCaptureFromTemplate($template, $account, null, null);
        $clone->setResponsible($user);
        $project->addRecurringCapture($clone);
        $em->persist($clone);
        $em->flush();

        return $this->redirectToRoute('app_project_edit', [
            'id' => $projectId,
        ]);
    }

    #[Route('/template/new', name: 'app_capture_template_new', methods: ['GET', 'POST'])]
    public function newTemplate(Request $request, EntityManagerInterface $em): Response
    {
        $capture = new Capture();
        $form = $this->createForm(CaptureTemplateNewForm::class, $capture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($capture);
            $em->flush();
            $this->activityLogLogger->logForCapture($capture, ActivityAction::TEMPLATE_CREATED, $this->getUser());
            return $this->redirectToRoute('app_capture_template_edit', ['id' => $capture->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('capture/new.html.twig', [
            'capture' => $capture,
            'form' => $form,
            'templateMode' => true,
        ]);
    }

    #[Route('/{id}/show', name: 'app_capture_show', methods: ['GET'])]
    #[Route('/template/{id}/show', name: 'app_capture_template_show', methods: ['GET'])]
    public function show(Request $request, Capture $capture): Response
    {
        $templateMode = 'app_capture_template_show' === $request->attributes->get('_route') || $capture->isTemplate();

        return $this->render('capture/show.html.twig', [
            'capture' => $capture,
            'templateMode' => $templateMode,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_capture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Capture $capture, ConditionToggler $toggler, EntityManagerInterface $em): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        $form = $this->createForm(CaptureContributorForm::class, $capture);
        $form->handleRequest($request);

        // Reset active state and apply conditions
        foreach ($capture->getCaptureElements() as $element) {
            $element->setActive(true);
        }
        $toggler->apply(is_iterable($capture->getConditions()) ? $capture->getConditions() : []);

        // Map conditions by target id for display
        $conditionsByTargetId = [];
        foreach ($capture->getConditions() as $cond) {
            $tid = $cond->getTargetElement()?->getId();
            if (null !== $tid) {
                $conditionsByTargetId[$tid][] = $cond;
            }
        }

        // refresh status
        $this->statusManager->refresh($capture, $user, true);

        $responseForms = [];
        // Build inline response forms for each element
        foreach ($capture->getCaptureElements() as $el) {
            $isDisabled = true;

            // 1) Active flag
            if ($el->isActive()) {
                $isDisabled = false;
            }

            // 2) Status
            if ($el->isReady() || $el->isSubmitted()) {
                $isDisabled = false;
            }

            $inlineForm = $this->createForm(CaptureElementContributorForm::class, $el, [
                'disabled' => $isDisabled,
            ]);
            $responseForms[$el->getId()] = $inlineForm->createView();
        }

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em->flush();
                $this->activityLogLogger->logForCapture($capture, ActivityAction::UPDATED, $user);
            } else {
                foreach ($form->getErrors(true, true) as $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
            }
        }
        $activity_logs = $this->activityLogProvider->forCapture($capture);

        return $this->render('capture/edit.html.twig', [
            'capture' => $capture,
            'form' => $form,
            'conditionsByTargetId' => $conditionsByTargetId,
            'templateMode' => false,
            'responseForms' => $responseForms,
            'activity_logs' => $activity_logs,
        ]);
    }

    #[Route('/template/{id}/edit', name: 'app_capture_template_edit', methods: ['GET', 'POST'])]
    public function editTemplate(Request $request, Capture $capture, ConditionToggler $toggler, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CaptureTemplateForm::class, $capture);
        $form->handleRequest($request);

        // Reset active state and apply conditions
        foreach ($capture->getCaptureElements() as $element) {
            $element->setActive(true);
        }
        $toggler->apply(is_iterable($capture->getConditions()) ? $capture->getConditions() : []);

        // Map conditions by target id for display
        $conditionsByTargetId = [];
        foreach ($capture->getConditions() as $cond) {
            $tid = $cond->getTargetElement()?->getId();
            if (null !== $tid) {
                $conditionsByTargetId[$tid][] = $cond;
            }
        }

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // update elements order
                $raw = (string) $request->request->get('elements_order', '[]');
                $orderedIds = json_decode($raw, true);
                if (is_array($orderedIds)) {
                    foreach ($orderedIds as $index => $id) {
                        $el = $em->getRepository(CaptureElement::class)->find((int) $id);
                        if ($el) {
                            $el->setPosition($index);
                        }
                    }
                }
                $em->flush();
                $this->activityLogLogger->logForCapture($capture, ActivityAction::TEMPLATE_UPDATED, $this->getUser());
            } else {
                foreach ($form->getErrors(true, true) as $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
            }
        }
        $projectId = $request->query->get('projectId');
        $activity_logs = $this->activityLogProvider->forCapture($capture);
        return $this->render('capture/edit.html.twig', [
            'capture' => $capture,
            'form' => $form,
            'conditionsByTargetId' => $conditionsByTargetId,
            'templateMode' => true,
            'projectId' => $projectId,
            'activity_logs' => $activity_logs,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_capture_delete', methods: ['POST'])]
    #[Route('/template/{id}/delete', name: 'app_capture_template_delete', methods: ['POST'])]
    public function delete(Request $request, Capture $capture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$capture->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($capture);
            $entityManager->flush();
            $this->activityLogLogger->logForCapture($capture, ActivityAction::DELETED, $this->getUser());
        }

        $route = $capture->isTemplate() ? 'app_capture_template_index' : 'app_capture_index';

        return $this->redirectToRoute($route, [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/render-preview', name: 'app_capture_render_text_preview', methods: ['GET'])]
    #[Route('/template/{id}/template-preview', name: 'app_capture_template_render_text_preview', methods: ['GET'])]
    public function renderPreview(?Profiler $profiler, Request $request, Capture $capture): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();
        $renderingConfig = $user->getTenant()->getRenderingConfig();

        /* remove symfony toolbar in modal */
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        if (null !== $profiler) {
            $profiler->disable();
        }

        $isTemplateRoute = 'app_capture_template_render_text_preview' === $request->attributes->get('_route') || $capture->isTemplate();

        return $this->render('capture/render_preview.html.twig', [
            'capture' => $capture,
            'templateMode' => $isTemplateRoute,
            'renderingConfig' => $renderingConfig,
        ]);
    }

    #[Route('/{captureId}/elements/{id}/attach', name: 'app_capture_template_attach_element', methods: ['GET'])]
    public function attachElement(#[MapEntity(id: 'captureId')] Capture $capture, CaptureElement $element, EntityManagerInterface $em): Response
    {
        if (!$capture->isTemplate()) {
            throw new NotFoundHttpException('Element attach is only available for templates.');
        }
        $capture->addCaptureElement($element);
        $em->flush();
        $this->activityLogLogger->logForCapture($capture, ActivityAction::TEMPLATE_UPDATED, $this->getUser());
        return $this->redirectToRoute('app_capture_template_edit', ['id' => $capture->getId()]);
    }

    #[Route('/template/{id}/publish', name: 'app_capture_template_publish', methods: ['GET'])]
    public function publishTemplate(Capture $capture, EntityManagerInterface $em): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if (!$capture->isDraft()) {
            throw new NotFoundHttpException('Ce template de capture ne peux pas être publiée');
        }
        // ToDo: Validation before publish
        $this->statusManager->publishTemplate($capture, $user);
        $em->flush();
        $this->activityLogLogger->logForCapture($capture, ActivityAction::PUBLISHED, $this->getUser());
        return $this->redirectToRoute('app_capture_template_edit', ['id' => $capture->getId()]);
    }

    #[Route('/template/{id}/unpublish', name: 'app_capture_template_unpublish', methods: ['GET'])]
    public function unpublishTemplate(Capture $capture, EntityManagerInterface $em): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        // ToDo: Validation before publish
        $this->statusManager->unpublishTemplate($capture, $user);
        $em->flush();
        $this->activityLogLogger->logForCapture($capture, ActivityAction::UNPUBLISHED, $this->getUser());
        return $this->redirectToRoute('app_capture_template_edit', ['id' => $capture->getId()]);
    }

    private function cloneCaptureFromTemplate(Capture $template, Account $account, ?string $name, ?string $description): Capture
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if (!$template->isTemplate()) {
            throw new NotFoundHttpException('Cette capture n’est pas un template.');
        }
        $clone = clone $template;
        $clone->setAccount($account);
        $this->statusManager->init($clone, $user, false);
        if (null !== $name && '' !== trim($name)) {
            $clone->setName($name);
        }
        if (null !== $description && '' !== trim($description)) {
            $clone->setDescription($description);
        }

        return $clone;
    }
}
