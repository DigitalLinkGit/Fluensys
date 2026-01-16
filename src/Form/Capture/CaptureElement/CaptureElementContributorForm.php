<?php

namespace App\Form\Capture\CaptureElement;

use App\Entity\Capture\CaptureElement\CaptureElement;
use App\Entity\Capture\CaptureElement\ListableFieldCaptureElement;
use App\Entity\Capture\Field\ListableField;
use App\Form\Capture\Field\FieldContributorForm;
use App\Service\Helper\FieldTypeManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureElementContributorForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('fields', CollectionType::class, [
            'entry_type' => FieldContributorForm::class,
            'entry_options' => ['label' => false],
            'label' => false,
            'by_reference' => false,
            'allow_add' => false,
            'allow_delete' => false,
            'prototype' => false,
        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CaptureElement::class,
        ]);
    }
}
