<?php

namespace App\Form\Field;

use App\Entity\Field\Field;
use App\Factory\FieldFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ConfigFieldForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label')
            ->add('technicalName')
            /*
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'texte long' => 'textarea',
                    'texte court' => 'text',
                    'nombre entier' => 'integer',
                    'nombre décimal' => 'decimal',
                ],
                'mapped' =>false,
                'data' => isset($options['data']) ? FieldFactory::getTypeFromInstance($options['data']) : 'textarea',

            ])
            */
            ->add('required')
            ->add('position');

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if (!$data instanceof Field) {
                return;
            }

            $form->add('type', ChoiceType::class, [
                'choices' => [
                    'texte long' => 'textarea',
                    'texte court' => 'text',
                    'nombre entier' => 'integer',
                    'nombre décimal' => 'decimal',
                ],
                'mapped' => false,
                'data' => FieldFactory::getTypeFromInstance($data),
            ]);
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Field::class,
            'empty_data' => function (FormInterface $form) {
                $type = $form->get('type')->getData() ?? 'textarea'; // fallback
                return FieldFactory::createFromType($type);
            },
        ]);
    }
}
