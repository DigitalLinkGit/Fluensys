<?php

namespace App\Form\Capture\Field;

use App\Entity\Capture\Field\TableField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TableFieldContributorForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('rows', CollectionType::class, [
            'entry_type' => TableFieldRowContributorForm::class,
            'entry_options' => [
                'label' => false,
                'columns' => $options['columns'] ?? [],
            ],
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'prototype' => true,
            'label' => false,
            'attr' => [
                'data-controller' => 'table-field',
                'data-table-field' => 'rows',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'columns' => [],
        ]);
        $resolver->setAllowedTypes('columns', 'array');
    }

}
