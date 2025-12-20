<?php

namespace App\Form\Capture\CaptureElement;

use App\Entity\Capture\CaptureElement\CaptureElement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureElementMinimalForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', HiddenType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de l\'élémént...',
                    'readonly' => true,
                ],
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 1,
                    'placeholder' => 'Description de la capture...',
                    'readonly' => true,
                ],
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Activé',
                'required' => false,
                'disabled' => true, // read-only côté serveur et côté navigateur
                'row_attr' => ['class' => 'form-check form-switch'], // Bootstrap 5 switch
                'label_attr' => ['class' => 'form-check-label'],
                'attr' => [
                    'class' => 'form-check-input',
                    'role' => 'switch', // sémantique ARIA (optionnel)
                    'readonly' => true,
                ],
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
