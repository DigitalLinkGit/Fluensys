<?php

namespace App\Form;

use App\Entity\CaptureElement;
use App\Entity\Field;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigFieldForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label')
            ->add('technicalName')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'textarea' => 'textarea',
                    'integer' => 'integer',
                    'etc' => 'etc',
                ],
                'mapped' =>false,
            ])
            ->add('required')
            ->add('position');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Field::class,
        ]);
    }
}
