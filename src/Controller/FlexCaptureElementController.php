<?php

namespace App\Controller;

use App\Dto\RenderTextDto;
use App\Entity\FlexCaptureElement;
use App\Entity\Rendering\ChapterContent;
use App\Factory\FieldFactory;
use App\Form\CaptureElement\CaptureElementTemplateForm;
use App\Repository\FlexCaptureElementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/flex-capture')]
final class FlexCaptureElementController extends AbstractController
{
    #[Route(name: 'app_flex_capture_element_index', methods: ['GET'])]
    public function index(FlexCaptureElementRepository $flexCaptureRepository): Response
    {
        $all = $flexCaptureRepository->findAll();
        $templates = array_filter($all, fn($el) => $el->isTemplate());
        return $this->render('flex_capture_element/index.html.twig', [
            'flex_captures' => $templates,
        ]);
    }

    #[Route('/new', name: 'app_flex_capture_element_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $flexCapture = new FlexCaptureElement();
        $form = $this->createForm(CaptureElementTemplateForm::class, $flexCapture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->processFields($form, $flexCapture, $entityManager);
            $this->persistFlexCapture($flexCapture,$entityManager);

            return $this->redirectToRoute('app_flex_capture_element_edit', [
                'id' => $flexCapture->getId(),
            ]);
        }

        return $this->render('flex_capture_element/new.html.twig', [
            'flex_capture' => $flexCapture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_flex_capture_element_show', methods: ['GET'])]
    public function show(FlexCaptureElement $flexCapture): Response
    {
        return $this->render('flex_capture_element/show.html.twig', [
            'flex_capture' => $flexCapture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_flex_capture_element_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FlexCaptureElement $flexCapture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CaptureElementTemplateForm::class, $flexCapture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->processFields($form, $flexCapture, $entityManager);
            $this->persistFlexCapture($flexCapture,$entityManager);

            return $this->redirectToRoute('app_flex_capture_element_edit', ['id'=> $flexCapture->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('flex_capture_element/edit.html.twig', [
            'flex_capture' => $flexCapture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_flex_capture_element_delete', methods: ['POST'])]
    public function delete(Request $request, FlexCaptureElement $flexCapture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$flexCapture->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($flexCapture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_flex_capture_element_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param FlexCaptureElement $flexCapture
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    public function processFields(\Symfony\Component\Form\FormInterface $form, FlexCaptureElement $flexCapture, EntityManagerInterface $entityManager): void
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

    public function persistFlexCapture(FlexCaptureElement $flexCapture, EntityManagerInterface $em): void
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
