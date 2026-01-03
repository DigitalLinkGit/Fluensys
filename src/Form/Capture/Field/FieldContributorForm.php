<?php

namespace App\Form\Capture\Field;

use App\Entity\Capture\Field\ChecklistField;
use App\Entity\Capture\Field\Field;
use App\Entity\Capture\Field\ListableField;
use App\Entity\Capture\Field\UrlField;
use App\Service\Helper\FieldTypeManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Url;

class FieldContributorForm extends AbstractType
{
    public function __construct(private readonly FieldTypeManager $typeManager)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $field = $event->getData();
            if (!$field instanceof Field) {
                return;
            }

            if ($field instanceof ListableField) {
                $event->getForm()->add('items', ListableFieldContributorForm::class, [
                    'inherit_data' => true,
                    'label' => $field->isRequired() ? '*'.$field->getLabel() : $field->getLabel(),
                    'required' => (bool) $field->isRequired(),
                ]);

                return;
            }

            $formFieldType = $this->typeManager->getFormTypeFor($field);

            $opts = [
                'data' => $field->getValue(),
                'label' => $field->isRequired() ? '*'.$field->getLabel() : $field->getLabel(),
                'required' => (bool) $field->isRequired(),
            ];

            if ($field instanceof ChecklistField) {
                $value = $field->getValue();

                if ($field->isUniqueResponse() && is_array($value)) {
                    $value = $value[0] ?? null;
                }

                if (!$field->isUniqueResponse() && !is_array($value) && null !== $value) {
                    $value = [$value];
                }

                $opts['data'] = $value;

                $opts += [
                    'choices' => $field->toSymfonyChoices(),
                    'expanded' => true,
                    'multiple' => !$field->isUniqueResponse(),
                ];
            }


            if ($field instanceof UrlField) {
                $opts += [
                    'default_protocol' => 'https',
                    'constraints' => [
                        new Url(),
                    ],
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
