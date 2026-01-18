<?php

namespace App\Controller\Capture;

use App\Controller\AbstractAppController;
use App\Entity\Capture\CaptureElement\FlexCaptureElement;
use App\Entity\Capture\Field\Field;
use App\Form\Capture\CaptureElement\CaptureElementTemplateForm;
use App\Service\Factory\FieldFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[Route('/flex-capture-element')]
final class FieldAjaxController extends AbstractAppController
{
    #[Route('/{id}/field/add', name: 'app_flex_capture_field_add', methods: ['POST'])]
    public function add(
        Request $request,
        FlexCaptureElement $element,
        EntityManagerInterface $em,
        FieldFactory $factory,
        FormFactoryInterface $formFactory,
        Environment $twig,
    ): JsonResponse {
        $type = (string) $request->request->get('type', '');
        if ('' === $type) {
            return new JsonResponse(['status' => 'error', 'message' => 'Missing field type'], 400);
        }

        $currentCount = $element->getFields()->count();

        // Provide minimal required defaults to satisfy non-null columns
        $defaultName = sprintf('Champ %d', $currentCount + 1);
        $data = [
            'name' => $defaultName,
            'label' => $defaultName,
            'required' => false,
            'position' => $currentCount,
        ];

        try {
            $field = $factory->createFromForm($type, $data);
            // add to element collection to keep both sides in sync and ensure form renders it
            $element->addField($field);

            $em->persist($element);
            $em->flush();
        } catch (\Throwable $e) {
            $this->logger->error('Field add failed: '.$e->getMessage(), ['exception' => $e]);

            return new JsonResponse(['status' => 'error', 'message' => 'Unable to create field'], 500);
        }

        // Rebuild parent form to render the new field with the existing template
        $form = $formFactory->create(CaptureElementTemplateForm::class, $element);
        $view = $form->createView();
        $childView = null;
        if (isset($view->children['fields'])) {
            foreach ($view->children['fields']->children as $child) {
                $value = $child->vars['value'] ?? null;
                if ($value && method_exists($value, 'getId') && $value->getId() === $field->getId()) {
                    $childView = $child;
                    break;
                }
            }
        }

        if (!$childView) {
            return new JsonResponse(['status' => 'error', 'message' => 'Field view not found'], 500);
        }

        $html = $twig->render('capture/field/_field_template_prototype.html.twig', [
            'form' => $childView,
        ]);

        return new JsonResponse([
            'status' => 'ok',
            'html' => $html,
            'fieldId' => $field->getId(),
        ]);
    }

    #[Route('/field/{id}/delete', name: 'app_flex_capture_field_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Field $field,
        EntityManagerInterface $em,
    ): JsonResponse {
        // $this->denyAccessUnlessGranted('EDIT', $field->getCaptureElement());

        $token = (string) $request->request->get('_token', '');
        $expected = 'delete_field_'.$field->getId();
        if (!$this->isCsrfTokenValid($expected, $token)) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid CSRF token'], 400);
        }

        $element = $field->getCaptureElement();
        $removedPos = $field->getPosition() ?? 0;

        try {
            $em->remove($field);
            // Compact positions of remaining fields for the same element
            foreach ($element->getFields() as $f) {
                if ($f === $field) {
                    continue;
                }
                $pos = (int) $f->getPosition();
                if ($pos > $removedPos) {
                    $f->setPosition($pos - 1);
                }
            }
            $em->flush();
        } catch (\Throwable $e) {
            $this->logger->error('Field delete failed: '.$e->getMessage(), ['exception' => $e]);

            return new JsonResponse(['status' => 'error', 'message' => 'Unable to delete field'], 500);
        }

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/{id}/field/reorder', name: 'app_flex_capture_field_reorder', methods: ['POST'])]
    public function reorder(
        Request $request,
        FlexCaptureElement $element,
        EntityManagerInterface $em,
    ): JsonResponse {
        // $this->denyAccessUnlessGranted('EDIT', $element);
        $payload = json_decode($request->getContent() ?: '[]', true);
        $order = $payload['order'] ?? [];
        if (!is_array($order)) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid order payload'], 400);
        }
        $map = array_flip(array_map('intval', $order));
        foreach ($element->getFields() as $f) {
            $id = (int) $f->getId();
            if (array_key_exists($id, $map)) {
                $f->setPosition((int) $map[$id]);
            }
        }
        try {
            $em->flush();
        } catch (\Throwable $e) {
            $this->logger->error('Field reorder failed: '.$e->getMessage(), ['exception' => $e]);

            return new JsonResponse(['status' => 'error', 'message' => 'Unable to save order'], 500);
        }

        return new JsonResponse(['status' => 'ok']);
    }
}
