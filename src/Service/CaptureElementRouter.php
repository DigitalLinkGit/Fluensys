<?php

namespace App\Service;

use App\Entity\Capture\CaptureElement\CaptureElement;

final class CaptureElementRouter
{
    public function resolveEditRoute(CaptureElement $element): array
    {
        $type = (new \ReflectionClass($element))->getShortName();
        $captureId = $element->getCapture()?->getId();

        return match ($type) {
            'FlexCaptureElement' => [
                'app_flex_capture_element_edit',
                [
                    'id' => $element->getId(),
                    'capture' => $captureId,
                ],
            ],
            'SystemComponentCaptureElement' => [
                'app_system_component_capture_element_edit',
                [
                    'id' => $element->getId(),
                    'capture' => $captureId,
                ],
            ],
            default => [
                'app_capture_element_index',
                [],
            ],
        };
    }

}
