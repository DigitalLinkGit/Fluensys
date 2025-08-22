<?php

namespace App\Form;

use App\Entity\Capture;
use App\Entity\CaptureElement;
use App\Form\CaptureElement\CaptureElementForm;
use App\Form\Field\ConfigFieldForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('captureElements', EntityType::class, [
                'class' => CaptureElement::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('captureElements', CollectionType::class, [
                'entry_type' => CaptureElementForm::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'by_reference' => false,
                'prototype' => true,
                'entry_options' => ['label' => false],
                'attr' => [
                    'data-controller'=> 'capture'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Capture::class,
        ]);
    }
}
