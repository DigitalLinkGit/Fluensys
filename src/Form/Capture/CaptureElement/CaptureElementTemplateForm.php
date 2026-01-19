<?php

namespace App\Form\Capture\CaptureElement;

use App\Entity\Capture\CaptureElement;
use App\Form\Capture\Field\FieldTemplateForm;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureElementTemplateForm extends CaptureElementBaseForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var CaptureElement $element */
        $element = $builder->getData();

        parent::buildForm($builder, $options);

        $builder->add('fields', CollectionType::class, [
            'entry_type' => FieldTemplateForm::class,
            'allow_add' => true,
            'allow_delete' => true,
            'label' => false,
            'by_reference' => false,
            'prototype' => true,
            'entry_options' => ['label' => false],
            'attr' => [
                'data-controller' => 'capture-element',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CaptureElement::class,
        ]);
    }
}
