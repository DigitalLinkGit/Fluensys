<?php

namespace App\Form\Capture\Field;

use App\Entity\Capture\Field\TextListField;
use App\Entity\Capture\Field\TextField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextListFieldForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('strings', CollectionType::class, [
                'entry_type' => ListableTextFieldForm::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'attr' => [
                    'data-controller' => 'classic-collection',
                ],
                'entry_options' => [
                    'label' => false,
                    'attr' => ['class' => 'row'],
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TextListField::class,
        ]);
    }
}
