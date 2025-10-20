<?php

namespace App\Controller\Capture;

use App\Controller\AbstractAppController;
use App\Entity\Capture\CaptureElement\SystemComponentCaptureElement;
use App\Form\Capture\CaptureElement\CaptureElementTemplateForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/components-capture-element')]
final class SystemComponentCaptureElementController extends AbstractAppController
{
    #[Route('/{id}/edit', name: 'app_system_component_capture_element_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SystemComponentCaptureElement $element, EntityManagerInterface $entityManager): Response
    {
        $captureId = $request->query->getInt('capture');

        $form = $this->createForm(CaptureElementTemplateForm::class, $element);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $this->processFields($form, $element, $entityManager);
                    $entityManager->persist($element);
                    $entityManager->flush();
                    $this->addFlash('success', 'Élément enregistré avec succès.');

                    return $this->redirectToRoute('app_system_component_capture_element_edit', [
                        'id' => $element->getId(),
                        'capture' => $captureId,
                    ]);
                } catch (\Throwable $e) {
                    $this->logger->error($e->getMessage(), ['exception' => $e]);
                    $this->addFlash('danger', 'Une erreur est survenue lors de l’enregistrement.');
                }
            } else {
                $this->addFlash('warning', 'Le formulaire contient des erreurs. Corrigez-les pour continuer.');
            }
        }

        return $this->render('capture/capture_element/system_component_capture_element/edit.html.twig', [
            'element' => $element,
            'form' => $form,
            'captureId' => $captureId,
        ]);
    }


    public function processFields(\Symfony\Component\Form\FormInterface $form, SystemComponentCaptureElement $element, EntityManagerInterface $entityManager): void
    {
        foreach ($form->get('fields') as $fieldForm) {
            $field = $fieldForm->getData();
            if (!$field) {
                continue;
            }

            $submittedType = (string) $fieldForm->get('type')->getData();
            if ($submittedType && $submittedType !== $field->getType()) {
                throw new \RuntimeException("Type mismatch: {$submittedType} vs {$field->getType()}");
            }

            $field->setCaptureElement($element);
            $element->addField($field);
            $entityManager->persist($field);
        }
    }
}
