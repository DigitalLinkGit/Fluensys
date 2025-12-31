<?php

namespace App\Controller\Capture;

use App\Entity\Account\Account;
use App\Entity\Capture\Capture;
use App\Entity\Capture\CaptureElement\CaptureElement;
use App\Entity\Tenant\User;
use App\Form\Capture\CaptureContributorForm;
use App\Form\Capture\CaptureContributorNewForm;
use App\Form\Capture\CaptureTemplateForm;
use App\Form\Capture\CaptureTemplateNewForm;
use App\Form\Participant\ParticipantAssignmentForm;
use App\Repository\CaptureRepository;
use App\Service\Helper\CaptureStatusManager;
use App\Service\Helper\ConditionToggler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/capture')]
final class CaptureController extends AbstractController
{
    #[Route(name: 'app_capture_index', methods: ['GET'])]
    #[Route('/templates', name: 'app_capture_template_index', methods: ['GET'])]
    public function index(Request $request, CaptureRepository $captureRepository): Response
    {
        $isTemplateIndex = 'app_capture_template_index' === $request->attributes->get('_route')
            || $request->query->getBoolean('template');

        $all = $captureRepository->findAll();
        $captures = array_filter($all, fn (Capture $el) => $isTemplateIndex ? $el->isTemplate() : !$el->isTemplate());

        return $this->render('capture/index.html.twig', [
            'captures' => $captures,
            'templateMode' => $isTemplateIndex,
        ]);
    }

    #[Route('/new', name: 'app_capture_new', methods: ['GET', 'POST'])]
    #[Route('/template/new', name: 'app_capture_template_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, CaptureStatusManager $statusManager): Response
    {
        $isTemplateRoute = 'app_capture_template_new' === $request->attributes->get('_route');

        if ($isTemplateRoute) {
            // Create a template
            $capture = new Capture();
            $form = $this->createForm(CaptureTemplateNewForm::class, $capture);
            $form->handleRequest($request);

            if ($form->isSubmitted() && !$form->isValid()) {
                foreach ($form->getErrors(true, true) as $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
            }

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($capture);
                $em->flush();

                return $this->redirectToRoute('app_capture_template_edit', ['id' => $capture->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('capture/new.html.twig', [
                'capture' => $capture,
                'form' => $form,
                'templateMode' => true,
            ]);
        }

        /** @var \App\Entity\Tenant\User|null $user */
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
            $clone->setResponsible($this->getUser());
            foreach ($clone->getCaptureElements() as $element) {
                $statusManager->refresh($element, $user, false);
            }

            $em->persist($clone);
            $em->flush();

            return $this->redirectToRoute('app_capture_participant_assign', ['id' => $clone->getId()]);
        }

        return $this->render('capture/new.html.twig', [
            'form' => $form,
            'templateMode' => false,
        ]);
    }

    #[Route('/{id}/assign', name: 'app_capture_participant_assign', methods: ['GET', 'POST'])]
    public function assignParticipant(Request $request, Capture $capture, EntityManagerInterface $em, CaptureStatusManager $statusManager): Response
    {
        if ($capture->isTemplate()) {
            throw new NotFoundHttpException('Assignment is not available on templates.');
        }

        /** @var \App\Entity\Tenant\User|null $user */
        $user = $this->getUser();
        $form = $this->createForm(ParticipantAssignmentForm::class, $capture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($capture->getCaptureElements() as $element) {
                $statusManager->refresh($element, $user, false);
            }
            $em->persist($capture);
            $em->flush();

            return $this->redirectToRoute('app_capture_edit', ['id' => $capture->getId()]);
        }

        return $this->render('participant_role/participant_assignment_form.html.twig', [
            'id' => $capture->getId(),
            'form' => $form,
            'capture' => $capture,
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
    #[Route('/template/{id}/edit', name: 'app_capture_template_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Capture $capture, ConditionToggler $toggler, CaptureStatusManager $statusManager, EntityManagerInterface $em): Response
    {
        $isTemplateRoute = 'app_capture_template_edit' === $request->attributes->get('_route') || $capture->isTemplate();

        if ($isTemplateRoute) {
            $form = $this->createForm(CaptureTemplateForm::class, $capture);
            $form->handleRequest($request);

            // Reset active state and apply conditions (template design mode)
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

            if ($form->isSubmitted() && !$form->isValid()) {
                foreach ($form->getErrors(true, true) as $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
            }

            if ($form->isSubmitted() && $form->isValid()) {
                // update elements order
                $raw = (string) $request->request->get('capture_elements_order', '[]');
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
            }

            return $this->render('capture/edit.html.twig', [
                'capture' => $capture,
                'form' => $form,
                'conditionsByTargetId' => $conditionsByTargetId,
                'templateMode' => true,
            ]);
        }

        // Instance (contributor) edit
        /** @var User|null $user */
        $user = $this->getUser();
        $form = $this->createForm(CaptureContributorForm::class, $capture);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em->persist($capture);
                $em->flush();
            } else {
                foreach ($form->getErrors(true, true) as $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
            }

            return $this->redirectToRoute('app_capture_edit', ['id' => $capture->getId()]);
        }

        // refresh elements status for instances
        foreach ($capture->getCaptureElements() as $element) {
            $statusManager->refresh($element, $user, false);
        }

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

        return $this->render('capture/edit.html.twig', [
            'capture' => $capture,
            'form' => $form,
            'conditionsByTargetId' => $conditionsByTargetId,
            'templateMode' => false,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_capture_delete', methods: ['POST'])]
    #[Route('/template/{id}/delete', name: 'app_capture_template_delete', methods: ['POST'])]
    public function delete(Request $request, Capture $capture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$capture->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($capture);
            $entityManager->flush();
        }

        $route = $capture->isTemplate() ? 'app_capture_template_index' : 'app_capture_index';

        return $this->redirectToRoute($route, [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/render-preview', name: 'app_capture_render_text_preview', methods: ['GET'])]
    #[Route('/template/{id}/template-preview', name: 'app_capture_template_render_text_preview', methods: ['GET'])]
    public function renderPreview(Request $request, Capture $capture): Response
    {
        $isTemplateRoute = 'app_capture_template_render_text_preview' === $request->attributes->get('_route') || $capture->isTemplate();
        return $this->render('capture/render_preview.html.twig', [
            'capture' => $capture,
            'templateMode' => $isTemplateRoute,
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

        return $this->redirectToRoute('app_capture_template_edit', ['id' => $capture->getId()]);
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
