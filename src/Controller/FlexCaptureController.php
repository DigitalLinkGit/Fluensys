<?php

namespace App\Controller;

use App\Entity\Field\Field;
use App\Entity\FlexCapture;
use App\Factory\FieldFactory;
use App\Form\CaptureElement\CaptureElementConfigForm;
use App\Form\CaptureElement\CaptureElementExternalForm;
use App\Form\CaptureElement\CaptureElementInternalForm;
use App\Form\Field\ExternalFieldForm;
use App\Repository\FlexCaptureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/flex-capture')]
final class FlexCaptureController extends AbstractController
{
    #[Route(name: 'app_flex_capture_index', methods: ['GET'])]
    public function index(FlexCaptureRepository $flexCaptureRepository): Response
    {
        return $this->render('flex_capture/index.html.twig', [
            'flex_captures' => $flexCaptureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_flex_capture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $flexCapture = new FlexCapture();
        $form = $this->createForm(CaptureElementConfigForm::class, $flexCapture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->processFields($form, $flexCapture, $entityManager);
            $this->persistFlexCapture($flexCapture,$entityManager);

            return $this->redirectToRoute('app_flex_capture_edit', [
                'id' => $flexCapture->getId(),
            ]);
        }

        return $this->render('flex_capture/new.html.twig', [
            'flex_capture' => $flexCapture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_flex_capture_show', methods: ['GET'])]
    public function show(FlexCapture $flexCapture): Response
    {
        return $this->render('flex_capture/show.html.twig', [
            'flex_capture' => $flexCapture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_flex_capture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FlexCapture $flexCapture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CaptureElementConfigForm::class, $flexCapture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->processFields($form, $flexCapture, $entityManager);
            $this->persistFlexCapture($flexCapture,$entityManager);

            return $this->redirectToRoute('app_flex_capture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('flex_capture/edit.html.twig', [
            'flex_capture' => $flexCapture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_flex_capture_delete', methods: ['POST'])]
    public function delete(Request $request, FlexCapture $flexCapture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$flexCapture->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($flexCapture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_flex_capture_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/external-preview', name: 'app_flex_capture_external_preview', methods: ['GET'])]
    public function externalPreview(FlexCapture $flexCapture): Response
    {
        $form = $this->createForm(CaptureElementExternalForm::class, $flexCapture);
        return $this->render('capture_element/preview.html.twig', [
            'flex_capture' => $flexCapture,
            'form'=>$form,
        ]);
    }

    #[Route('/{id}/internal-preview', name: 'app_flex_capture_internal_preview', methods: ['GET'])]
    public function internalPreview(FlexCapture $flexCapture): Response
    {
        $form = $this->createForm(CaptureElementInternalForm::class, $flexCapture);
        return $this->render('capture_element/preview.html.twig', [
            'flex_capture' => $flexCapture,
            'form'=>$form,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param FlexCapture $flexCapture
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    public function processFields(\Symfony\Component\Form\FormInterface $form, FlexCapture $flexCapture, EntityManagerInterface $entityManager): void
    {
        foreach ($form->get('fields') as $index => $fieldForm) {
            $type = $fieldForm->get('type')->getData();
            $rawField = $fieldForm->getData();

            $typedField = FieldFactory::createTypedField($rawField, $type);
            $typedField->setCaptureElement($flexCapture);

            $flexCapture->getFields()->set($index, $typedField);

            $entityManager->persist($typedField);
            $entityManager->remove($rawField); // si orphanRemoval ou cascade persist est actif
        }
    }

    public function persistFlexCapture(FlexCapture $flexCapture, EntityManagerInterface $em): void
    {
        $fields = $flexCapture->getFields();

        // On trie ceux qui ont déjà une position
        $ordered = $fields->filter(fn($f) => $f->getPosition() !== null)
            ->toArray();

        usort($ordered, fn($a, $b) => $a->getPosition() <=> $b->getPosition());

        // On ajoute ceux qui n’ont pas de position à la fin
        $noPosition = $fields->filter(fn($f) => $f->getPosition() === null);

        $final = array_merge($ordered, $noPosition->toArray());

        foreach ($final as $index => $field) {
            $field->setPosition($index);
        }

        $em->persist($flexCapture);
        $em->flush();
    }

}
