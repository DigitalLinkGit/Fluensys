<?php

namespace App\Service\Factory;

use App\Entity\Capture\Field\ChecklistField;
use App\Entity\Capture\Field\DateField;
use App\Entity\Capture\Field\DecimalField;
use App\Entity\Capture\Field\Field;
use App\Entity\Capture\Field\IntegerField;
use App\Entity\Capture\Field\SystemComponentCollectionField;
use App\Entity\Capture\Field\TextAreaField;
use App\Entity\Capture\Field\TextField;
use App\Form\Capture\Field\SystemComponentCollectionFieldForm;

class FieldFactoryOld
{
    private const TYPE_MAP = [
        'textarea' => TextAreaField::class,
        'text'     => TextField::class,
        'integer'  => IntegerField::class,
        'decimal'  => DecimalField::class,
        'date'  => DateField::class,
        'checklist' => ChecklistField::class,
        'system_component_collection' => SystemComponentCollectionField::class,
    ];

    public static function newFromType(string $type): Field
    {
        $class = self::TYPE_MAP[$type] ?? null;

        if (!$class || !is_subclass_of($class, Field::class)) {
            throw new \InvalidArgumentException("Type de champ invalide : $type");
        }

        return new $class();
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
            $field instanceof SystemComponentCollectionField => 'system_component_collection',
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
            $field instanceof SystemComponentCollectionField => SystemComponentCollectionFieldForm::class,
            default => throw new \LogicException('Type de champ non supporté pour ' . get_class($field)),
        };
    }
}
