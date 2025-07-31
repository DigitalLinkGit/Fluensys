<?php

namespace App\Form;

use App\Entity\CaptureElement;
use App\Entity\ParticipantRole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureElementForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de l\'élémént...',
                ],
                'required' => true,
            ])
            ->add('participantRoles', EntityType::class, [
                'class' => ParticipantRole::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'by_reference' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Description de la capture...',
                ],
            ])
            ->add('fields', CollectionType::class, [
                'entry_type' => FieldForm::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'by_reference' => false,
                'prototype' => true,
                'entry_options' => ['label' => false],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CaptureElement::class,
        ]);
    }
}
