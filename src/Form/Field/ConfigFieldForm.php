<?php

namespace App\Form\Field;

use App\Entity\Field\Field;
use App\Entity\FieldFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;


class ConfigFieldForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label')
            ->add('technicalName')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'texte long' => 'textarea',
                    'texte court' => 'text',
                    'nombre entier' => 'integer',
                    'nombre décimal' => 'decimal',
                ],
                'mapped' =>false,
            ])
            ->add('required')
            ->add('position');

        /*// Form listener sur PRE_SET_DATA
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if (!isset($data['type'])) {
                return;
            }

            // Crée dynamiquement le bon sous-type
            $field = FieldFactory::createFromType($data['type']);

            // On copie les données simples déjà soumises
            $field->setLabel($data['label'] ?? '');
            $field->setTechnicalName($data['technicalName'] ?? '');
            $field->setRequired(!empty($data['required']));
            $field->setPosition((int) ($data['position'] ?? 0));

            $event->setData($field);
        });
        */
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
