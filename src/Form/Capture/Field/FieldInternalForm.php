<?php

namespace App\Form\Capture\Field;

use App\Entity\Capture\Field\ChecklistField;
use App\Entity\Capture\Field\Field;
use App\Entity\Capture\Field\FieldConfig;
use App\Entity\Capture\Field\SystemComponentCollectionField;
use App\Service\Helper\FieldTypeHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldInternalForm extends AbstractType
{
    public function __construct(private readonly FieldTypeHelper $typeHelper)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var FieldConfig|null $cfgOpt */
        $cfgOpt = $options['config'];
        /** @var string $scope */
        $scope = $options['config_scope'];

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($cfgOpt, $scope) {
            $field = $event->getData();
            if (!$field instanceof Field) {
                return;
            }

            $config = $cfgOpt ?? ('internal' === $scope ? $field->getInternalConfig() : $field->getExternalConfig());

            if (!$config instanceof FieldConfig) {
                return;
            }

            if ($field instanceof SystemComponentCollectionField) {
                $event->getForm()->add('value', SystemComponentCollectionFieldForm::class, [
                    'label' => $config->getLabel(),
                    'inherit_data' => true,
                ]);

                return;
            }

            $formFieldType = $this->typeHelper->getFormTypeFor($field);

            $opts = [
                'data' => $field->getValue(),
                'label' => $config->isRequired() ? '*'.$config->getLabel() : $config->isRequired(),
                'required' => (bool) $config->isRequired(),
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
            'config' => null,
            'config_scope' => 'internal',
        ]);

        $resolver->setAllowedTypes('config', [FieldConfig::class, 'null']);
        $resolver->setAllowedValues('config_scope', ['internal', 'external']);
    }
}
