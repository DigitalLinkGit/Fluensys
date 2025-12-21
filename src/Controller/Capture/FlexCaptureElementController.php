<?php

namespace App\Controller\Capture;

use App\Controller\AbstractAppController;
use App\Entity\Capture\CaptureElement\FlexCaptureElement;
use App\Form\Capture\CaptureElement\CaptureElementTemplateForm;
use App\Service\Helper\FieldTypeHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/flex-capture-element')]
final class FlexCaptureElementController extends AbstractAppController
{
    #[Route('/{id}/edit', name: 'app_flex_capture_element_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FlexCaptureElement $flexCapture, EntityManagerInterface $entityManager, FieldTypeHelper $helper): Response
    {

        $captureId = $request->query->getInt('capture');

        $form = $this->createForm(CaptureElementTemplateForm::class, $flexCapture);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {

                    $entityManager->persist($flexCapture);
                    $entityManager->flush();
                    $this->addFlash('success', 'Élément enregistré avec succès.');

                    return $this->redirectToRoute('app_flex_capture_element_edit', [
                        'id' => $flexCapture->getId(),
                        'capture' => $captureId,
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
            'element' => $flexCapture,
            'form' => $form,
            'dragTypes' => $helper->getLibraryChoices(true),
            'captureId' => $captureId,
        ]);
    }

}
