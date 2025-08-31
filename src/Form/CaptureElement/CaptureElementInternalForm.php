<?php

namespace App\Form\CaptureElement;

use App\Entity\CaptureElement;
use App\Form\Field\InternalFieldForm;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureElementInternalForm extends CaptureElementForm
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
            ->add('fields', CollectionType::class, [
                'entry_type' => InternalFieldForm::class,
                'label' => false,
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
