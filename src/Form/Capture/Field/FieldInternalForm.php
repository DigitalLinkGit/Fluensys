<?php

namespace App\Form\Capture\Field;

use App\Entity\Capture\Field\Field;
use App\Service\Factory\FieldFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldInternalForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $field = $event->getData();

            if (!$field instanceof Field) return;

            $formFieldType = \App\Service\Factory\FieldFactory::getSymfonyTypeFromInstance($field);

            $options = [
                'data' => $field->getValue(),
                'label' => $field->getInternalLabel(),
                'required' => false,
            ];
            if ($field instanceof \App\Entity\Capture\Field\ChecklistField) {
                $options = array_replace($options, [
                    'choices' => $field->toSymfonyChoices(),
                    'expanded' => true,
                    'multiple' => true,
                ]);
            }
            $event->getForm()->add('value', $formFieldType, $options);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Field::class,
        ]);
    }

}
