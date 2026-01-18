<?php

namespace App\Form\Capture\Field;

use App\Entity\Capture\Field\TableFieldColumn;
use App\Entity\Capture\Field\TableFieldRow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TableFieldRowContributorForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var TableFieldColumn[] $columns */
        $columns = $options['columns'];

        foreach ($columns as $col) {
            $key = (string) $col->getKey();
            $type = (string) $col->getType();

            $formType = match ($type) {
                'integer' => IntegerType::class,
                'date' => DateType::class,
                default => TextType::class,
            };

            $fieldOptions = [
                'label' => (string) $col->getLabel(),
                'required' => false,
                'property_path' => sprintf('values[%s]', $key),
            ];

            if (DateType::class === $formType) {
                $fieldOptions['widget'] = 'single_text';
                $fieldOptions['input'] = 'string';
            }

            $builder->add($key, $formType, $fieldOptions);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TableFieldRow::class,
            'columns' => [],
        ]);
    }
}
