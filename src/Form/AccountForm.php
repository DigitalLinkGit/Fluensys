<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\InformationSystem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('informationSystem', EntityType::class, [
                'class' => InformationSystem::class,
                'choice_label' => 'id',
            ])
            ->add('contacts', CollectionType::class, [
                'entry_type' => ContactForm::class,
                'allow_add' => true,
                'disabled' => false,
                'allow_delete' => true,
                'label' => false,
                'by_reference' => false,
                'prototype' => true,
                'entry_options' => [
                    'label' => false,
                    'attr' => ['class' => 'row']
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
