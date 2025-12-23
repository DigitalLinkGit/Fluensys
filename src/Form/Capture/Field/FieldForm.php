<?php

namespace App\Form\Capture\Field;

use App\Entity\Capture\Field\ChecklistField;
use App\Entity\Capture\Field\Field;
use App\Entity\Capture\Field\SystemComponentCollectionField;
use App\Service\Helper\FieldTypeHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldForm extends AbstractType
{
    public function __construct(private readonly FieldTypeHelper $typeHelper)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $field = $event->getData();
            if (!$field instanceof Field) {
                return;
            }


            if ($field instanceof SystemComponentCollectionField) {
                $event->getForm()->add('value', SystemComponentCollectionFieldForm::class, [
                    'label' => $field->getLabel(),
                    'inherit_data' => true,
                ]);

                return;
            }

            $formFieldType = $this->typeHelper->getFormTypeFor($field);

            $opts = [
                'data' => $field->getValue(),
                'label' => $field->isRequired() ? '*'.$field->getLabel() : $field->getLabel(),
                'required' => (bool) $field->isRequired(),
            ];

            if ($field instanceof ChecklistField) {
                $opts += [
                    'choices' => $field->toSymfonyChoices(),
                    'expanded' => true,
                    'multiple' => true,
                ];
            }

            $event->getForm()->add('value', $formFieldType, $opts);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Field::class,
        ]);

    }
}
