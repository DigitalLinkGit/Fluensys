<?php

namespace App\Service\Factory;

use App\Service\Helper\CaptureElementTypeManager;

final readonly class CaptureElementFactory
{
    public function __construct(private CaptureElementTypeManager $typeManager)
    {
    }

    public function create(string $typeKey, array $data): object
    {
        $class = $this->typeManager->resolveClass($typeKey);

        $element = new $class();

        $this->setIfCallable($element, 'setName', $data['name'] ?? null);
        $this->setIfCallable($element, 'setDescription', $data['description'] ?? null);
        $this->setIfCallable($element, 'setContributor', $data['contributor'] ?? null);
        $this->setIfCallable($element, 'setValidator', $data['validator'] ?? null);
        $this->setIfCallable($element, 'setPosition', $data['position'] ?? null);

        return $element;
    }

    private function setIfCallable(object $obj, string $method, mixed $value): void
    {
        if (null !== $value && is_callable([$obj, $method])) {
            $obj->{$method}($value);
        }
    }
}
