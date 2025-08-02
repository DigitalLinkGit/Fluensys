<?php

namespace App\Factory;

use App\Entity\Field\DateField;
use App\Entity\Field\DecimalField;
use App\Entity\Field\Field;
use App\Entity\Field\IntegerField;
use App\Entity\Field\TextareaField;
use App\Entity\Field\TextField;

class FieldFactory
{
    private const TYPE_MAP = [
        'textarea' => TextareaField::class,
        'text'     => TextField::class,
        'integer'  => IntegerField::class,
        'decimal'  => DecimalField::class,
        'date'  => DateField::class,
    ];

    public static function createFromType(string $type): Field
    {
        $class = self::TYPE_MAP[$type] ?? null;

        if (!$class || !is_subclass_of($class, Field::class)) {
            throw new \InvalidArgumentException("Type de champ invalide : $type");
        }

        return new $class();
    }

    public static function createTypedField(Field $base, string $type): Field
    {
        $typed = self::createFromType($type);
        return self::fromBase($base, $typed);
    }

    private static function fromBase(Field $base, Field $typed): Field
    {
        $typed->setExternalLabel($base->getExternalLabel());
        $typed->setInternalLabel($base->getInternalLabel());
        $typed->setTechnicalName($base->getTechnicalName());
        $typed->setRequired($base->isRequired());

        $typed->setCaptureElement($base->getCaptureElement());

        return $typed;
    }



    public static function getTypeFromInstance(?Field $field): string
    {
        if (!$field) {
            return 'text';
        }

        return match (true) {
            $field instanceof TextAreaField => 'textarea',
            $field instanceof TextField => 'text',
            $field instanceof IntegerField => 'integer',
            $field instanceof DecimalField => 'decimal',
            $field instanceof DateField => 'date',
            default => throw new \LogicException('Type de champ non supporté'),
        };
    }

    public static function getSymfonyTypeFromInstance(Field $field): string
    {
        return match (true) {
            $field instanceof TextareaField => \Symfony\Component\Form\Extension\Core\Type\TextareaType::class,
            $field instanceof TextField => \Symfony\Component\Form\Extension\Core\Type\TextType::class,
            $field instanceof IntegerField => \Symfony\Component\Form\Extension\Core\Type\IntegerType::class,
            $field instanceof DecimalField => \Symfony\Component\Form\Extension\Core\Type\NumberType::class,
            $field instanceof DateField => \Symfony\Component\Form\Extension\Core\Type\DateType::class,
            default => throw new \LogicException('Type de champ non supporté pour ' . get_class($field)),
        };
    }
}
