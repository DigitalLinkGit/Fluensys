<?php
namespace App\Service\Factory;

use App\Entity\Capture\CaptureElement;

final class CaptureElementFactory
{
    public function __construct(private readonly CaptureElementTypeHelper $typeHelper) {}

    public function createFromForm(string $typeKey, array $data): object
    {
        $class = $this->typeHelper->resolveClass($typeKey); // ex: \App\Entity\FlexCapture::class
        $element = new $class();

        $this->setIfCallable($element, 'setName', $data['name'] ?? null);
        $this->setIfCallable($element, 'setDescription', $data['description'] ?? null);
        $this->setIfCallable($element, 'setRespondent', $data['respondent'] ?? null);
        $this->setIfCallable($element, 'setResponsible', $data['responsible'] ?? null);
        $this->setIfCallable($element, 'setValidator', $data['validator'] ?? null);

        return $element;
    }

    private function setIfCallable(object $obj, string $method, mixed $value): void
    {
        if ($value !== null && is_callable([$obj, $method])) {
            $obj->{$method}($value);
        }
    }
}
