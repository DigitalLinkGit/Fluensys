<?php

namespace App\Controller\Capture;

use App\Entity\Capture\CaptureElement\CaptureElement;
use App\Entity\Capture\Field\Field;
use App\Repository\CaptureElementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ConditionAjaxController extends AbstractController
{
    #[Route('/condition/fields', name: 'condition_fields', methods: ['GET'])]
    public function fields(Request $request, CaptureElementRepository $elements): JsonResponse
    {
        $id = $request->query->get('sourceElement');
        if (!$id) {
            return new JsonResponse(['fields' => []]);
        }

        /** @var CaptureElement|null $element */
        $element = $elements->find($id);
        if (!$element || !method_exists($element, 'getFields')) {
            return new JsonResponse(['fields' => []]);
        }

        $fields = $element->getFields();
        $array = [];
        foreach (is_iterable($fields) ? $fields : [] as $f) {
            /* @var Field $f */
            $array[] = ['id' => $f->getId(), 'label' => $f->getTechnicalName()];
        }

        return new JsonResponse(['fields' => $array]);
    }
}
