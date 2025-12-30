<?php

namespace App\Controller\Capture;

use App\Controller\AbstractAppController;
use App\Entity\Capture\CaptureElement\FlexCaptureElement;
use App\Entity\Capture\CaptureElement\ListableFieldCaptureElement;
use App\Entity\Capture\Field\Field;
use App\Form\Capture\CaptureElement\CaptureElementTemplateForm;
use App\Form\Capture\CaptureElement\ListableFieldCaptureElementTemplateForm;
use App\Service\Helper\FieldTypeManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/listable-field-capture-element')]
final class ListableFieldCaptureElementController extends AbstractAppController
{
    #[Route('/{id}/edit', name: 'app_listable_field_capture_element_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ListableFieldCaptureElement $element, EntityManagerInterface $entityManager, FieldTypeManager $helper): Response
    {
        $form = $this->createForm(CaptureElementTemplateForm::class, $element);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {

                    $entityManager->persist($element);
                    $entityManager->flush();
                    $this->addFlash('success', 'Élément enregistré avec succès.');

                    return $this->redirectToRoute('app_listable_field_capture_element_edit', [
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

        return $this->render('capture/capture_element/listable_field_capture_element_edit.html.twig', [
            'element' => $element,
            'form' => $form,
            'dragTypes' => $helper->getLibraryChoices(true),
            'captureId' => $element->getCapture()->getId(),
        ]);
    }
}
