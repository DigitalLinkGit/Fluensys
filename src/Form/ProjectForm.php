<?php

namespace App\Form;

use App\Entity\Capture;
use App\Entity\Project;
use App\Entity\InformationSystem;
use App\Form\Capture\CaptureInternalForm;
use App\Form\Capture\CaptureMinimalForm;
use App\Form\CaptureElement\CaptureElementMinimalForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom du projet...',
                ],
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Description du projet...',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Draft' => 'draft',
                    'En cours' => 'inProgress',
                    'En pause' => 'paused',
                    'Terminé' => 'finished',
                ],
            ])
            ->add('informationSystem', EntityType::class, [
                'class' => InformationSystem::class,
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez un système d\'information',
                'required' => false,
                'label' => 'Système d\'information',
                'attr' => [
                    'class' => 'form-select'
                ],
            ])
            ->add('captures', CollectionType::class, [
                'entry_type' => CaptureInternalForm::class,
                'allow_add' => true,
                'allow_delete' => false,
                'label' => false,
                'by_reference' => false,
                'prototype' => true,
                'entry_options' => ['label' => false],
                'attr' => [
                    'data-controller'=> 'project_template'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
