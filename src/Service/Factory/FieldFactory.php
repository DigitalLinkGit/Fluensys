<?php
// src/Service/Factory/FieldFactory.php
namespace App\Service\Factory;

use App\Entity\Capture\Field\Field;
use App\Service\Helper\FieldTypeHelper;

final readonly class FieldFactory
{
    public function __construct(private FieldTypeHelper $typeHelper) {}

    /**
     * @param string $typeKey  Discriminator (ex: 'text', 'textarea', 'integer', ...)
     * @param array  $data     form data
     */
    public function createFromForm(string $typeKey, array $data): Field
    {
        $class = $this->typeHelper->resolveClass($typeKey);
        /** @var Field $field */
        $field = new $class();

        $this->setIfCallable($field, 'setInternalLabel', $data['internalLabel'] ?? null);
        $this->setIfCallable($field, 'setExternalLabel', $data['externalLabel'] ?? null);
        $this->setIfCallable($field, 'setName', $data['name'] ?? null);
        $this->setIfCallable($field, 'internalPosition', $data['internalPosition'] ?? null);
        $this->setIfCallable($field, 'setInternalRequired', $data['internalRequired'] ?? false);
        $this->setIfCallable($field, 'setExternalRequired', $data['externalRequired'] ?? false);

        // Spécifiques selon sous-type (exemples)
        // if (method_exists($field, 'setChoices') && isset($data['choices'])) {
        //     $field->setChoices($data['choices']); // tableau normalisé ['key' => 'Label', ...]
        // }

        // if ($field instanceof \App\Entity\CaptureTemplate\Field\DecimalField) {
        //     $this->setIfCallable($field, 'setScale', $data['scale'] ?? null);
        //     $this->setIfCallable($field, 'setMin', $data['min'] ?? null);
        //     $this->setIfCallable($field, 'setMax', $data['max'] ?? null);
        // }

        return $field;
    }

    private function setIfCallable(object $obj, string $method, mixed $value): void
    {
        if ($value !== null && is_callable([$obj, $method])) {
            $obj->{$method}($value);
        }
    }
}
