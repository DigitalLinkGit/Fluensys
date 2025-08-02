<?php

namespace App\Form\Field;

use App\Entity\Field\Field;
use App\Entity\Field\TextAreaField;
use App\Factory\FieldFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExternalFieldForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $field = $event->getData();

            if (!$field instanceof Field) {
                return;
            }

            $formFieldType = FieldFactory::getSymfonyTypeFromInstance($field);

            $event->getForm()->add('value', $formFieldType, [
                'data' => $field->getValue(),
                'label' => $field->getExternalLabel(),
                'required' => $field->isRequired(),
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
