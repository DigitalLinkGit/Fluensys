<?php

namespace App\Factory;

use App\Entity\Field\ChecklistField;
use App\Entity\Field\DateField;
use App\Entity\Field\DecimalField;
use App\Entity\Field\Field;
use App\Entity\Field\IntegerField;
use App\Entity\Field\TextAreaField;
use App\Entity\Field\TextField;

class FieldFactory
{
    private const TYPE_MAP = [
        'textarea' => TextAreaField::class,
        'text'     => TextField::class,
        'integer'  => IntegerField::class,
        'decimal'  => DecimalField::class,
        'date'  => DateField::class,
        'checklist' => ChecklistField::class,
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

        // Transfer subtype-specific data when both are same subtype
        if ($base instanceof \App\Entity\Field\ChecklistField && $typed instanceof \App\Entity\Field\ChecklistField) {
            $typed->setChoices($base->getChoices());
            $typed->setValue($base->getValue());
        }

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
            $field instanceof ChecklistField => 'checklist',
            default => throw new \LogicException('Type de champ non supporté'),
        };
    }

    public static function getSymfonyTypeFromInstance(Field $field): string
    {
        return match (true) {
            $field instanceof TextAreaField => \Symfony\Component\Form\Extension\Core\Type\TextareaType::class,
            $field instanceof TextField => \Symfony\Component\Form\Extension\Core\Type\TextType::class,
            $field instanceof IntegerField => \Symfony\Component\Form\Extension\Core\Type\IntegerType::class,
            $field instanceof DecimalField => \Symfony\Component\Form\Extension\Core\Type\NumberType::class,
            $field instanceof DateField => \Symfony\Component\Form\Extension\Core\Type\DateType::class,
            $field instanceof ChecklistField => \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class,
            default => throw new \LogicException('Type de champ non supporté pour ' . get_class($field)),
        };
    }
}
