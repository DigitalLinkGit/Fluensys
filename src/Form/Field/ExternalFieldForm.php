<?php

namespace App\Form\Field;

use App\Entity\Field\Field;
use App\Entity\Field\TextAreaField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExternalFieldForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            if ($data instanceof TextAreaField) {
                $event->getForm()->add('value', TextAreaField::class, [
                    'data' => $data->getValue(),
                    'label' => $data->getLabel(),
                    'required' => $data->isRequired(),
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Field::class,
        ]);
    }
}
