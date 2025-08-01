<?php

namespace App\Factory;

use App\Entity\Field\Field;
use App\Entity\Field\IntegerField;
use App\Entity\Field\TextareaField;

class FieldFactory
{
    public static function createTypedField(Field $base, string $type): Field
    {
        return match ($type) {
            'textarea' => self::fromBase($base, new TextareaField()),
            'integer' => self::fromBase($base, new IntegerField()),
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
            'integer' => new IntegerField(),
            //'date' => new DateField(),

            default => throw new \InvalidArgumentException("Unknown field type: $type"),
        };
    }

    public static function getTypeFromInstance(?Field $field): string
    {
        if (!$field) {
            return 'textarea';
        }

        return match (true) {
            $field instanceof TextAreaField => 'textarea',
            //$field instanceof TextField => 'text',
            $field instanceof IntegerField => 'integer',
            //$field instanceof DecimalField => 'decimal',
            default => throw new \LogicException('Type de champ non supporté'),
        };
    }

}
