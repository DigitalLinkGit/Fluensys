<?php

namespace App\Form\CaptureElement;

use App\Entity\CaptureElement;
use App\Form\Field\FieldInternalForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureElementInternalForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fields', CollectionType::class, [
                'entry_type' => FieldInternalForm::class,
                'label' => false,
                'entry_options' => ['label' => false],
            ])
            ->add('active', CheckboxType::class, [
                'label'     => 'Activé',
                'required'  => false,
                'disabled'  => true,
                'row_attr'  => ['class' => 'form-check form-switch'],
                'label_attr'=> ['class' => 'form-check-label'],
                'attr'      => [
                    'class' => 'form-check-input',
                    'role'  => 'switch',
                    'readonly' => true
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
