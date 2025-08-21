<?php

namespace App\Form;

use App\Entity\CaptureElement;
use App\Entity\Field\Field;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('externalLabel')
            ->add('internalLabel')
            ->add('technicalName')
            ->add('required')
            ->add('position')
            ->add('captureElement', EntityType::class, [
                'class' => CaptureElement::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Field::class,
        ]);
    }
}
