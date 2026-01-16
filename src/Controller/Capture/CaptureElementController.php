<?php

namespace App\Controller\Capture;

use App\Controller\AbstractAppController;
use App\Entity\Capture\Capture;
use App\Entity\Capture\CaptureElement\CaptureElement;
use App\Entity\Capture\CaptureElement\FlexCaptureElement;
use App\Entity\Capture\Rendering\TextChapter;
use App\Entity\Tenant\User;
use App\Form\Capture\CaptureElement\CaptureElementContributorForm;
use App\Form\Capture\CaptureElement\CaptureElementTemplateNewForm;
use App\Form\Capture\CaptureElement\CaptureElementValidationForm;
use App\Form\Capture\Rendering\RenderTextEditorForm;
use App\Service\Factory\CaptureElementFactory;
use App\Service\Helper\CaptureElementRouter;
use App\Service\Helper\LivecycleStatusManager;
use App\Service\Helper\CaptureElementTypeManager;
use App\Service\Helper\ConditionToggler;
use App\Service\Rendering\TemplateInterpolator;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/capture/element')]
final class CaptureElementController extends AbstractAppController
{
    #[Route(name: 'app_capture_element_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $all = $em->getRepository(CaptureElement::class)->findAll();
        $templates = array_filter($all, fn ($el) => $el->isTemplate());

        return $this->render('capture/capture_element/index.html.twig', [
            'capture_elements' => $all,
        ]);
    }

    #[Route('/{id}/show', name: 'app_capture_element_show', methods: ['GET'])]
    public function show(CaptureElement $captureElement): Response
    {
        return $this->render('capture/capture_element/show.html.twig', [
            'capture_element' => $captureElement,
        ]);
    }

    #[Route('/{id}/preview', name: 'app_capture_element_form_preview', methods: ['GET'])]
    public function formPreview(CaptureElement $element): Response
    {
        $form = $this->createForm(CaptureElementContributorForm::class, $element);

        return $this->render('capture/capture_element/preview.html.twig', [
            'templateMode' => true,
            'element' => $element,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/render-text/edit', name: 'app_capture_element_render_text_edit', methods: ['GET', 'POST'])]
    public function editRenderText(Request $request, FlexCaptureElement $flexCapture, TemplateInterpolator $interpolator, EntityManagerInterface $entityManager): Response
    {
        // Build available variables from Fields and CalculatedVariables
        $fieldVars = [];
        foreach ($flexCapture->getFields() as $f) {
            $name = $f->getTechnicalName();
            if ($name) {
                $fieldVars[] = $name;
            }
        }

        $calcVars = [];
        foreach ($flexCapture->getCalculatedVariables() as $cv) {
            $name = $cv->getTechnicalName();
            if ($name) {
                $calcVars[] = $name;
            }
        }
        $variables = array_values(array_unique(array_merge($fieldVars, $calcVars)));

        $chapter = $flexCapture->getChapter() ?? new TextChapter();
        $form = $this->createForm(RenderTextEditorForm::class, $chapter, [
            'variables' => $variables,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            foreach ($form->getErrors(true, true) as $error) {
                $this->addFlash('danger', $error->getMessage());
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $flexCapture->setChapter($chapter);
            $entityManager->persist($chapter);
            $entityManager->flush();
            $this->addFlash('success', 'Rendu texte enregistré.');

            return $this->redirectToRoute('app_flex_capture_element_edit', ['id' => $flexCapture->getId()]);
        }

        return $this->render('capture/capture_element/render_text_editor.html.twig', [
            'form' => $form,
            'variables' => $variables,
            'element' => $flexCapture,
        ]);
    }

    #[Route('/{id}/respond', name: 'app_capture_element_respond', methods: ['POST'])]
    public function respond(Request $request, CaptureElement $element, EntityManagerInterface $entityManager, ConditionToggler $toggler, LivecycleStatusManager $statusManager): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        $capture = $element->getCapture();

        $form = $this->createForm(CaptureElementContributorForm::class, $element);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $statusManager->submit($element, $user, false);

            if (null !== $capture) {
                $toggler->apply($capture->getConditions()->toArray());
            }

            $entityManager->persist($element);
            $entityManager->flush();

            $this->addFlash('success', 'Votre réponse a bien été enregistrée');
        } else {
            foreach ($form->getErrors(true, true) as $error) {
                $this->addFlash('danger', $error->getMessage());
            }
        }

        return $this->redirectToRoute('app_capture_edit', ['id' => $capture?->getId()], 303);
    }

    #[Route('/{id}/valid', name: 'app_capture_element_valid', methods: ['GET', 'POST'])]
    public function valid(Request $request, CaptureElement $element, EntityManagerInterface $entityManager, ConditionToggler $toggler, LivecycleStatusManager $statusManager): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        $form = $this->createForm(CaptureElementValidationForm::class, $element);
        $form->handleRequest($request);
        $capture = $element->getCapture();

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // TODO: make reel validation
                $isValidated = true;
                /** @var \Symfony\Component\Form\FormInterface $fieldsForm */
                $fieldsForm = $form->get('fields');
                foreach ($fieldsForm as $index => $fieldItemForm) {
                    $isValidated = (bool) $fieldItemForm->get('validated')->getData();
                    if (!$isValidated) {
                        break;
                    }
                }
                if ($isValidated) {
                    $statusManager->validate($element, $user, false);
                    $this->addFlash('success', 'Enregistrement réussi. L\'élément à été validé');
                } else {
                    $this->addFlash('warning', 'Enregistrement réussi. L\'élément n\'est pas valide');
                }
                $entityManager->persist($element);
                $entityManager->flush();
                $this->addFlash('success', 'Votre réponse à bien été enregistrée');
            } else {
                foreach ($form->getErrors(true, true) as $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
            }

            return $this->redirectToRoute('app_capture_edit', ['id' => $capture?->getId()], 303);
        }

        return $this->render('capture/capture_element/valid.html.twig', [
            'element' => $element,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_capture_element_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, CaptureElementTypeManager $typeManager, CaptureElementFactory $factory, CaptureElementRouter $router): Response
    {
        $captureId = $request->query->getInt('capture');

        $form = $this->createForm(CaptureElementTemplateNewForm::class, null, [
            'type_choices' => $typeManager->getFormChoices(),
        ])->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();
                $typeKey = $form->get('type')->getData();

                $element = $factory->create($typeKey, $data);
                if ($captureId) {
                    $capture = $em->getRepository(Capture::class)->find($captureId);
                    if ($capture) {
                        $element->setCapture($capture);
                    }
                }

                $em->persist($element);
                $em->flush();
                [$route, $params] = $router->resolveEditRoute($element);

                return $this->redirectToRoute($route, $params);
            } else {
                foreach ($form->getErrors(true, true) as $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
            }
        }

        return $this->render('capture/capture_element/new.html.twig', [
            'form' => $form->createView(),
            'captureId' => $captureId,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_capture_element_delete', methods: ['POST'])]
    public function delete(Request $request, CaptureElement $element, EntityManagerInterface $entityManager): Response
    {
        $capture = $element->getCapture();

        if (!$this->isCsrfTokenValid('delete'.$element->getId(), $request->getPayload()->getString('_token'))) {
            $this->addFlash('danger', 'Jeton CSRF invalide. Suppression annulée.');

            return $this->redirectToRoute(
                'app_capture_template_edit',
                ['id' => $capture->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        try {
            // 1) Remove conditions referencing this element (source or target)
            $removedConditions = 0;

            foreach ($capture->getConditions() as $condition) {
                if ($condition->getSourceElement() === $element || $condition->getTargetElement() === $element) {
                    $entityManager->remove($condition);
                    ++$removedConditions;
                }
            }

            // 2) Remove the element itself
            $entityManager->remove($element);
            $entityManager->flush();

            if ($removedConditions > 0) {
                $this->addFlash(
                    'success',
                    sprintf('L’élément a bien été supprimé (%d condition(s) associée(s) supprimée(s)).', $removedConditions)
                );
            } else {
                $this->addFlash('success', 'L’élément a bien été supprimé.');
            }
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->addFlash(
                'warning',
                'Impossible de supprimer cet élément : il est encore référencé par d’autres données.'
            );
        } catch (\Throwable $e) {
            $this->logger?->error('Erreur lors de la suppression', [
                'id' => $element->getId(),
                'exception' => $e,
            ]);
            $this->addFlash('danger', 'Une erreur inattendue est survenue pendant la suppression.');
        }

        return $this->redirectToRoute(
            'app_capture_template_edit',
            ['id' => $capture->getId()],
            Response::HTTP_SEE_OTHER
        );
    }

    #[Route('/{id}/edit', name: 'app_capture_element_edit', methods: ['GET'])]
    public function editRedirect(int $id, Request $request, EntityManagerInterface $em, CaptureElementRouter $router): Response
    {
        $captureId = $request->query->getInt('capture');
        $element = $em->getRepository(CaptureElement::class)->find($id);
        if (!$element) {
            throw $this->createNotFoundException('CaptureElement not found');
        }

        [$route, $params] = $router->resolveEditRoute($element);
        if ($captureId) {
            $params['capture'] = $captureId;
        }

        return $this->redirectToRoute($route, $params);
    }
}
