<?php

namespace App\Entity;

use App\Entity\Field\Field;
use App\Entity\Field\TextareaField;

class FieldFactory
{
    public static function createTypedField(Field $base, string $type): Field
    {
        return match ($type) {
            'textarea' => self::fromBase($base, new TextareaField()),
            // 'number' => self::fromBase($base, new NumberField()),
            // autres types ici
            default => throw new \InvalidArgumentException("Type de champ invalide : $type"),
        };
    }

    private static function fromBase(Field $base, Field $typed): Field
    {
        $typed->setLabel($base->getLabel());
        $typed->setTechnicalName($base->getTechnicalName());
        $typed->setRequired($base->isRequired());
        $typed->setPosition($base->getPosition());


        $typed->setCaptureElement($base->getCaptureElement());

        return $typed;
    }

    public static function createFromType(string $type): Field
    {
        return match ($type) {
            'textarea' => new TextareaField(),
            //'date' => new DateField(),
            //'number' => new NumberField(),
            default => throw new \InvalidArgumentException("Unknown field type: $type"),
        };
    }
}
