<?php

namespace App\Form\CaptureElement;

use App\Entity\CaptureElement;
use App\Entity\Participant\ParticipantRole;
use App\Form\Field\FieldTemplateForm;
use App\Repository\ParticipantRoleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureElementTemplateForm extends AbstractType
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
            ->add('respondent', EntityType::class, [
                'class' => ParticipantRole::class,
                'choice_label' => 'name',
                'query_builder' => function (ParticipantRoleRepository $r) {
                    return $r->createQueryBuilder('role')
                        ->orderBy('role.name', 'ASC');
                },
                'placeholder' => 'Sélectionner un rôle...',
            ])
            ->add('responsible', EntityType::class, [
                'class' => ParticipantRole::class,
                'choice_label' => 'name',
                'query_builder' => function (ParticipantRoleRepository $r) {
                    return $r->createQueryBuilder('role')
                        ->orderBy('role.name', 'ASC');
                },
                'placeholder' => 'Sélectionner un rôle...',
            ])
            ->add('validator', EntityType::class, [
                'class' => ParticipantRole::class,
                'choice_label' => 'name',
                'query_builder' => function (ParticipantRoleRepository $r) {
                    return $r->createQueryBuilder('role')
                        ->orderBy('role.name', 'ASC');
                },
                'placeholder' => 'Sélectionner un rôle...',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Description de la capture...',
                ],
            ])
            ->add('fields', CollectionType::class, [
                'entry_type' => FieldTemplateForm::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'by_reference' => false,
                'prototype' => true,
                'entry_options' => ['label' => false],
                'attr' => [
                    'data-controller'=> 'capture-element'
                ]
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
