<?php

namespace App\Form\Capture\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class FieldValidationForm extends AbstractType
{
    public function getParent(): string
    {
        return FieldForm::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('validated', CheckboxType::class, [
            'label' => 'Validé',
            'required' => false,
            'mapped' => false,
        ]);
    }
}
