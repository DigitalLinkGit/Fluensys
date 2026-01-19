<?php

namespace App\Controller\Capture;

use App\Controller\AbstractAppController;
use App\Entity\Capture\Capture;
use App\Entity\Capture\CaptureElement;
use App\Entity\Capture\Field\Field;
use App\Entity\Capture\Field\FileField;
use App\Entity\Capture\Field\ImageField;
use App\Entity\Capture\Field\TableField;
use App\Entity\Capture\Rendering\Chapter;
use App\Entity\Enum\LivecycleStatus;
use App\Entity\Interface\UploadableField;
use App\Entity\Tenant\User;
use App\Form\Capture\CaptureElement\CaptureElementContributorForm;
use App\Form\Capture\CaptureElement\CaptureElementTemplateForm;
use App\Form\Capture\CaptureElement\CaptureElementValidationForm;
use App\Form\Capture\Rendering\RenderTextEditorForm;
use App\Service\Factory\CaptureElementFactory;
use App\Service\Helper\CaptureElementRouter;
use App\Service\Helper\CaptureElementTypeManager;
use App\Service\Helper\ConditionToggler;
use App\Service\Helper\FieldTypeManager;
use App\Service\Helper\FileUploadManager;
use App\Service\Helper\LivecycleStatusManager;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/capture/element')]
final class CaptureElementController extends AbstractAppController
{
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
        dump('PREVIEW element fields', [
            'element_id' => $element->getId(),
            'fields' => array_map(static function ($f) {
                if (!$f instanceof Field) {
                    return ['class' => is_object($f) ? get_class($f) : gettype($f)];
                }

                $row = [
                    'field_id' => $f->getId(),
                    'class' => get_class($f),
                ];

                if ($f instanceof TableField) {
                    $row['columns_count'] = $f->getColumns()->count();
                    $row['columns_ids'] = array_map(
                        static fn ($c) => method_exists($c, 'getId') ? $c->getId() : null,
                        $f->getColumns()->toArray()
                    );
                }

                return $row;
            }, $element->getFields()->toArray()),
        ]);

        return $this->render('capture/capture_element/preview.html.twig', [
            'templateMode' => true,
            'element' => $element,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/render-text/edit', name: 'app_capture_element_render_text_edit', methods: ['GET', 'POST'])]
    public function editRenderText(Request $request, CaptureElement $element, EntityManagerInterface $entityManager): Response
    {
        // Build available variables from Fields and CalculatedVariables
        $fieldVars = [];
        foreach ($element->getFields() as $f) {
            $name = $f->getTechnicalName();
            if ($name) {
                $fieldVars[] = $name;
            }
        }

        $calcVars = [];
        foreach ($element->getCalculatedVariables() as $cv) {
            $name = $cv->getTechnicalName();
            if ($name) {
                $calcVars[] = $name;
            }
        }
        $variables = array_values(array_unique(array_merge($fieldVars, $calcVars)));

        $chapter = $element->getChapter() ?? new Chapter();
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
            $element->setChapter($chapter);
            $entityManager->persist($chapter);
            $entityManager->flush();
            $this->addFlash('success', 'Rendu texte enregistré.');

            return $this->redirectToRoute('app_capture_element_edit', ['id' => $element->getId()]);
        }

        return $this->render('capture/capture_element/render_text_editor.html.twig', [
            'form' => $form,
            'variables' => $variables,
            'element' => $element,
        ]);
    }

    #[Route('/{id}/respond', name: 'app_capture_element_respond', methods: ['POST'])]
    public function respond(Request $request, CaptureElement $element, EntityManagerInterface $entityManager, ConditionToggler $toggler, LivecycleStatusManager $statusManager, FileUploadManager $fileUploadManager): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        $capture = $element->getCapture();

        $form = $this->createForm(CaptureElementContributorForm::class, $element);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('fields')) {
                foreach ($form->get('fields') as $fieldForm) {
                    $field = $fieldForm->getData();
                    if ($field instanceof ImageField) {
                        $uploadedFile = null;

                        if ($fieldForm->has('image') && $fieldForm->get('image')->has('value')) {
                            $uploadedFile = $fieldForm->get('image')->get('value')->getData();
                        }

                        $this->persistUploadedFile($uploadedFile, $field, $element, $capture, $fileUploadManager, 'images');
                    }

                    if ($field instanceof FileField) {
                        $uploadedFile = null;

                        if ($fieldForm->has('value')) {
                            $uploadedFile = $fieldForm->get('value')->getData();
                        }

                        $this->persistUploadedFile($uploadedFile, $field, $element, $capture, $fileUploadManager, 'files');
                    }
                }
            }

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

        $element = new CaptureElement();
        $form = $this->createForm(CaptureElementTemplateForm::class, $element);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($captureId) {
                    $capture = $em->getRepository(Capture::class)->find($captureId);
                    if ($capture) {
                        if ($capture->isTemplate()) {
                            $element->setStatus(LivecycleStatus::TEMPLATE);
                        }
                        $element->setCapture($capture);
                    }
                }

                $em->persist($element);
                $em->flush();

                return $this->redirectToRoute('app_capture_element_edit', ['id' => $element?->getId()], 303);
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

    #[Route('/{id}/edit', name: 'app_capture_element_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CaptureElement $element, EntityManagerInterface $entityManager, FieldTypeManager $helper): Response
    {
        $form = $this->createForm(CaptureElementTemplateForm::class, $element);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    // update elements order
                    $raw = (string) $request->request->get('fields_order', '[]');
                    $orderedIds = json_decode($raw, true);

                    if (is_array($orderedIds)) {
                        foreach ($orderedIds as $index => $id) {
                            $field = $entityManager->getRepository(Field::class)->find((int) $id);
                            if (!$field) {
                                continue;
                            }
                            $field->setPosition($index);
                        }
                    }

                    // Sync TableField columns from subtype[columns_raw]
                    $root = $form->getName(); // usually "capture_element_template_form" (Symfony decides)
                    $payload = $request->request->all($root);

                    if (isset($payload['fields']) && is_array($payload['fields'])) {
                        foreach ($form->get('fields') as $fieldForm) {
                            $field = $fieldForm->getData();
                            if (!$field instanceof TableField) {
                                continue;
                            }

                            $entryKey = $fieldForm->getName(); // key of this row in the collection
                            $rawColumns = $payload['fields'][$entryKey]['subtype']['columns_raw'] ?? null;
                            $field->syncColumnsFromRaw(is_string($rawColumns) ? $rawColumns : null);
                        }
                    }

                    $entityManager->persist($element);
                    $entityManager->flush();
                    $this->addFlash('success', 'Élément enregistré avec succès.');

                    return $this->redirectToRoute('app_capture_element_edit', [
                        'id' => $element->getId(),
                        'capture' => $element->getCapture()->getId(),
                    ]);
                } catch (\Throwable $e) {
                    $this->logger->error($e->getMessage(), ['exception' => $e]);
                    $this->addFlash('danger', $e->getMessage());
                }
            } else {
                $this->addFlash('warning', 'Le formulaire contient des erreurs. Corrigez-les pour continuer.');
            }
        }

        return $this->render('capture/capture_element/edit.html.twig', [
            'element' => $element,
            'form' => $form,
            'dragTypes' => $helper->getLibraryChoices(true),
            'captureId' => $element->getCapture()->getId(),
        ]);
    }

    private function persistUploadedFile(
        ?UploadedFile $uploadedFile,
        UploadableField $field,
        CaptureElement $element,
        ?Capture $capture,
        FileUploadManager $fileUploadManager,
        string $folder,
    ): void {
        if (null === $uploadedFile) {
            return;
        }

        $captureId = $capture?->getId() ?? 0;
        $elementId = $element->getId() ?? 0;

        $subDir = sprintf('captures/%d/elements/%d/%s', $captureId, $elementId, $folder);
        $baseName = $field->getTechnicalName() ?: ($field->getName() ?: 'file');

        $oldPath = $field->getPath();
        $newPath = $fileUploadManager->upload($uploadedFile, $subDir, $baseName);

        $field->setPath($newPath);
        $field->setValue($uploadedFile->getClientOriginalName());

        if ($oldPath && $oldPath !== $newPath) {
            $fileUploadManager->delete($oldPath);
        }
    }
}
