<?php

namespace App\Form;

use App\Entity\Field;
use App\Entity\TextAreaField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            if ($data instanceof TextAreaField) {
                $event->getForm()->add('value', TextareaType::class, [
                    'data' => $data->getValue(),
                    'label' => $data->getLabel(),
                ]);
            }
        });

/**
        $builder
            ->add('label')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'textarea' => 'textarea',
                    'integer' => 'integer',
                    'etc' => 'etc',
                ],
                'mapped' =>false,
            ])
            ->add('technicalName')
            ->add('required')
            ->add('position')
        ;
**/
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Field::class,
        ]);
    }
}
