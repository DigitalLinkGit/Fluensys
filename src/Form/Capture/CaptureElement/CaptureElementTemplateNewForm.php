<?php

namespace App\Form\Capture\CaptureElement;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureElementTemplateNewForm extends CaptureElementBaseForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => $options['type_choices'],
                'choice_label' => fn ($value, $label) => (string) $label,
                'choice_value' => fn ($value) => $value,
                'placeholder' => 'Choisir un type',
                'attr' => [
                    'class' => 'w-auto',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'type_choices' => [],
        ]);
        $resolver->setAllowedTypes('type_choices', 'array');
    }
}
