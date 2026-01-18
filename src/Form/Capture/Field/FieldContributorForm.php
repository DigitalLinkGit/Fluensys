<?php

namespace App\Form\Capture\Field;

use App\Entity\Capture\Field\ChecklistField;
use App\Entity\Capture\Field\Field;
use App\Entity\Capture\Field\FileField;
use App\Entity\Capture\Field\ImageField;
use App\Entity\Capture\Field\ListableField;
use App\Entity\Capture\Field\TableField;
use App\Entity\Capture\Field\UrlField;
use App\Service\Helper\FieldTypeManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Url;

class FieldContributorForm extends AbstractType
{
    public function __construct(private readonly FieldTypeManager $typeManager)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $field = $event->getData();
            if (!$field instanceof Field) {
                return;
            }

            $form = $event->getForm();

            $key = $this->typeManager->getKeyFor($field);
            $fieldFormType = $this->typeManager->getFormTypeFor($field);

            $form->add('type', HiddenType::class, [
                'mapped' => false,
                'required' => true,
                'data' => $key,
            ]);

            // Special contributor forms (inherit_data)
            if ($field instanceof TableField) {
                $form->add('rows', TableFieldContributorForm::class, [
                    'inherit_data' => true,
                    'label' => $this->buildLabel($field),
                    'required' => (bool) $field->isRequired(),
                    'columns' => $field->getColumns()->toArray(),
                ]);

                return;
            }

            if ($field instanceof ListableField) {
                $form->add('items', ListableFieldContributorForm::class, [
                    'inherit_data' => true,
                    'label' => $this->buildLabel($field),
                    'required' => (bool) $field->isRequired(),
                ]);

                return;
            }

            if ($field instanceof ImageField) {
                $form->add('image', ImageFieldContributorForm::class, [
                    'inherit_data' => true,
                    'label' => $this->buildLabel($field),
                    'required' => (bool) $field->isRequired(),
                ]);

                return;
            }

            // File field (mapped=false, constraints)
            if ($field instanceof FileField) {
                $form->add('value', FileType::class, $this->withWAutoAttr(
                    [
                        'mapped' => false,
                        'label' => $this->buildLabel($field),
                        'required' => (bool) $field->isRequired(),
                        'constraints' => [new File(maxSize: '20M')],
                        'attr' => [],
                    ],
                    $options,
                    $key
                ));

                return;
            }

            // Generic value field
            $opts = $this->withWAutoAttr(
                [
                    'data' => $field->getValue(),
                    'label' => $this->buildLabel($field),
                    'required' => (bool) $field->isRequired(),
                    'attr' => [],
                ],
                $options,
                $key
            );

            if ($field instanceof ChecklistField) {
                $opts['data'] = $this->normalizeChecklistValue($field);

                $opts += [
                    'choices' => $field->toSymfonyChoices(),
                    'expanded' => true,
                    'multiple' => !$field->isUniqueResponse(),
                ];
            }

            if ($field instanceof UrlField) {
                $opts += [
                    'default_protocol' => 'https',
                    'constraints' => [new Url()],
                ];
            }

            $form->add('value', $fieldFormType, $opts);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Field::class,
            // list of field keys (FieldTypeManager::getKeyFor) that should receive "w-auto"
            // special value "*" applies to all
            'w_auto_fields' => ['date','text','integer','decimal','url','email','file'],
        ]);

        $resolver->setAllowedTypes('w_auto_fields', 'array');
    }

    private function buildLabel(Field $field): string
    {
        return $field->isRequired() ? '*'.$field->getLabel() : $field->getLabel();
    }

    private function normalizeChecklistValue(ChecklistField $field): mixed
    {
        $value = $field->getValue();

        if ($field->isUniqueResponse() && is_array($value)) {
            return $value[0] ?? null;
        }

        if (!$field->isUniqueResponse() && !is_array($value) && null !== $value) {
            return [$value];
        }

        return $value;
    }

    private function withWAutoAttr(array $opts, array $formOptions, string $fieldKey): array
    {
        $targets = $formOptions['w_auto_fields'] ?? [];

        $apply = in_array('*', $targets, true) || in_array($fieldKey, $targets, true);
        if (!$apply) {
            return $opts;
        }

        $opts['attr'] ??= [];
        $opts['attr']['class'] = trim(($opts['attr']['class'] ?? '').' w-auto');

        return $opts;
    }
}
