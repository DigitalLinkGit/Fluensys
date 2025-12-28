<?php

// src/Service/Factory/FieldFactory.php

namespace App\Service\Factory;

use App\Entity\Capture\Field\Field;
use App\Service\Helper\FieldTypeManager;

final readonly class FieldFactory
{
    public function __construct(private FieldTypeManager $typeHelper)
    {
    }

    /**
     * @param string $typeKey Discriminator (ex: 'text', 'textarea', 'integer', ...)
     * @param array  $data    form data
     */
    public function createFromForm(string $typeKey, array $data): Field
    {
        $class = $this->typeHelper->resolveClass($typeKey);
        /** @var Field $field */
        $field = new $class();

        $this->setIfCallable($field, 'setLabel', $data['label'] ?? null);
        $this->setIfCallable($field, 'setHelp', $data['help'] ?? null);
        $this->setIfCallable($field, 'setName', $data['name'] ?? null);
        $this->setIfCallable($field, 'setRequired', $data['required'] ?? null);
        $this->setIfCallable($field, 'setPosition', $data['position'] ?? null);


        // Spécifiques selon sous-type
        // if (method_exists($field, 'setChoices') && isset($data['choices'])) {
        //     $field->setChoices($data['choices']); // tableau normalisé ['key' => 'Label', ...]
        // }

        // if ($field instanceof \App\Entity\Capture\Field\DecimalField) {
        //     $this->setIfCallable($field, 'setScale', $data['scale'] ?? null);
        //     $this->setIfCallable($field, 'setMin', $data['min'] ?? null);
        //     $this->setIfCallable($field, 'setMax', $data['max'] ?? null);
        // }

        return $field;
    }

    private function setIfCallable(object $obj, string $method, mixed $value): void
    {
        if (null !== $value && is_callable([$obj, $method])) {
            $obj->{$method}($value);
        }
    }
}
