<?php

namespace App\Controller;

use App\Entity\FlexCaptureElement;
use App\Form\CaptureElement\CaptureElementTemplateForm;
use App\Repository\FlexCaptureElementRepository;
use App\Service\Factory\FieldFactory;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[Route('/flex-capture')]
final class FlexCaptureElementController extends AbstractAppController
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

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $this->processFields($form, $flexCapture, $entityManager);
                    $this->persistFlexCapture($flexCapture, $entityManager);

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

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $this->processFields($form, $flexCapture, $entityManager);
                    $this->persistFlexCapture($flexCapture, $entityManager);

                    $this->addFlash('success', 'Élément enregistré avec succès.');
                    return $this->redirectToRoute('app_flex_capture_element_edit', [
                        'id' => $flexCapture->getId(),
                    ]);
                } catch (\Throwable $e) {
                    //$this->logger->error($e->getMessage(), ['exception' => $e]);
                    $this->addFlash('danger', 'Une erreur est survenue lors de l’enregistrement.');
                }
            } else {
                $this->addFlash('warning', 'Le formulaire contient des erreurs. Corrigez-les pour continuer.');
            }
        }

        return $this->render('flex_capture_element/edit.html.twig', [
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
