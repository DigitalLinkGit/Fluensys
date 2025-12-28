<?php

namespace App\Controller\Capture;

use App\Controller\AbstractAppController;
use App\Entity\Capture\CaptureElement\FlexCaptureElement;
use App\Entity\Capture\Field\Field;
use App\Form\Capture\CaptureElement\CaptureElementTemplateForm;
use App\Service\Helper\FieldTypeManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/flex-capture-element')]
final class FlexCaptureElementController extends AbstractAppController
{
    #[Route('/{id}/edit', name: 'app_flex_capture_element_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FlexCaptureElement $element, EntityManagerInterface $entityManager, FieldTypeManager $helper): Response
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
                    $entityManager->persist($element);
                    $entityManager->flush();
                    $this->addFlash('success', 'Élément enregistré avec succès.');

                    return $this->redirectToRoute('app_flex_capture_element_edit', [
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

        return $this->render('capture/capture_element/flex_capture_element/edit.html.twig', [
            'element' => $element,
            'form' => $form,
            'dragTypes' => $helper->getLibraryChoices(true),
            'captureId' => $element->getCapture()->getId(),
        ]);
    }
}
