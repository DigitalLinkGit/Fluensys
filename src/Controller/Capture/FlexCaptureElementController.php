<?php

namespace App\Controller\Capture;

use App\Controller\AbstractAppController;
use App\Entity\Capture\CaptureElement\FlexCaptureElement;
use App\Entity\Capture\Field\SystemComponentCollectionField;
use App\Entity\SystemComponentCaptureElement;
use App\Form\Capture\CaptureElement\CaptureElementTemplateForm;
use App\Repository\FlexCaptureElementRepository;
use App\Service\Factory\FieldFactory;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/flex-capture')]
final class FlexCaptureElementController extends AbstractAppController
{
    #[Route(name: 'app_flex_capture_element_index', methods: ['GET'])]
    public function index(FlexCaptureElementRepository $flexCaptureRepository): Response
    {
        $all = $flexCaptureRepository->findAll();
        $templates = array_filter($all, fn($el) => $el->isTemplate());
        return $this->render('capture/flex_capture_element/index.html.twig', [
            'flex_captures' => $templates,
        ]);
    }

    #[Route('/new', name: 'app_flex_capture_element_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $flexCapture = new FlexCaptureElement();
        $form = $this->createForm(CaptureElementTemplateForm::class, $flexCapture);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $this->processFields($form, $flexCapture, $entityManager);
                    $entityManager->persist($flexCapture);
                    $entityManager->flush();
                    $this->addFlash('success', 'Élément créé avec succès.');
                    return $this->redirectToRoute('app_flex_capture_element_edit', [
                        'id' => $flexCapture->getId(),
                    ]);
                } catch (\Throwable $e) {
                    $this->logger->error($e->getMessage(), ['exception' => $e]);
                    $this->addFlash('danger', 'Une erreur est survenue lors de l’enregistrement.');
                }
            } else {
                $this->addFlash('warning', 'Le formulaire contient des erreurs. Corrigez-les pour continuer.');
            }
        }

        return $this->render('capture/flex_capture_element/new.html.twig', [
            'flex_capture' => $flexCapture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_flex_capture_element_show', methods: ['GET'])]
    public function show(FlexCaptureElement $flexCapture): Response
    {
        return $this->render('capture/flex_capture_element/show.html.twig', [
            'flex_capture' => $flexCapture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_flex_capture_element_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FlexCaptureElement $flexCapture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CaptureElementTemplateForm::class, $flexCapture);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $this->processFields($form, $flexCapture, $entityManager);
                    $entityManager->persist($flexCapture);
                    $entityManager->flush();
                    $this->addFlash('success', 'Élément enregistré avec succès.');
                    return $this->redirectToRoute('app_flex_capture_element_edit', [
                        'id' => $flexCapture->getId(),
                    ]);
                } catch (\Throwable $e) {
                    $this->logger->error($e->getMessage(), ['exception' => $e]);
                    $this->addFlash('danger', 'Une erreur est survenue lors de l’enregistrement.');
                }
            } else {
                $this->addFlash('warning', 'Le formulaire contient des erreurs. Corrigez-les pour continuer.');
            }
        }

        return $this->render('capture/flex_capture_element/edit.html.twig', [
            'flex_capture' => $flexCapture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_flex_capture_element_delete', methods: ['POST'])]
    public function delete(Request $request, FlexCaptureElement $flexCapture, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete' . $flexCapture->getId(), $request->getPayload()->getString('_token'))) {
            $this->addFlash('danger', 'Jeton CSRF invalide. Suppression annulée.');
            return $this->redirectToRoute('app_flex_capture_element_index', [], Response::HTTP_SEE_OTHER);
        }

        try {
            $entityManager->remove($flexCapture);
            $entityManager->flush();

            $this->addFlash('success', 'L’élément a bien été supprimé.');
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->addFlash('warning', 'Impossible de supprimer cet élément car il est utilisé dans au moins une capture.');
        } catch (\Throwable $e) {
            $this->logger?->error('Erreur lors de la suppression', ['id' => $flexCapture->getId(), 'exception' => $e]);
            $this->addFlash('danger', 'Une erreur inattendue est survenue pendant la suppression.');
        }

        return $this->redirectToRoute('app_flex_capture_element_index', [], Response::HTTP_SEE_OTHER);
    }

    public function processFields(\Symfony\Component\Form\FormInterface $form, FlexCaptureElement $flexCapture, EntityManagerInterface $entityManager): void
    {
        foreach ($form->get('fields') as $fieldForm) {
            $field = $fieldForm->getData();
            if (!$field) continue;

            $submittedType = (string) $fieldForm->get('type')->getData();
            if ($submittedType && $submittedType !== $field->getType()) {
                throw new \RuntimeException("Type mismatch: {$submittedType} vs {$field->getType()}");
            }

            $field->setCaptureElement($flexCapture);
            $flexCapture->addField($field);
            $entityManager->persist($field);
        }
    }

}
