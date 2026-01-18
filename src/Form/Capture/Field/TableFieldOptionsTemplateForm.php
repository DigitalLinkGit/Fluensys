<?php

namespace App\Form\Capture\Field;

use App\Entity\Capture\Field\TableField;
use App\Entity\Capture\Field\TableFieldColumn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TableFieldOptionsTemplateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('columns', CollectionType::class, [
            'entry_type' => TableFieldColumnTemplateForm::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'prototype' => true,
            'label' => 'Colonnes',
            'prototype_options' => [
                'row_attr' => [
                    'data-table-field-item' => '1',
                    'class' => 'mb-2 border rounded p-2',
                ],
            ],
        ]);


        // Ensure positions + auto-key generation when saving
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
            $table = $event->getData();
            if (!$table instanceof TableField) {
                return;
            }

            $seen = [];
            $pos = 0;

            foreach ($table->getColumns() as $col) {
                if (!$col instanceof TableFieldColumn) {
                    continue;
                }

                $col->setPosition($pos);
                ++$pos;

                $key = trim((string) $col->getKey());
                if ('' === $key) {
                    $key = $this->normalizeKey((string) $col->getLabel());
                    if ('' === $key) {
                        $key = 'col';
                    }
                } else {
                    $key = $this->normalizeKey($key);
                }

                $base = $key;
                $i = 2;
                while (in_array($key, $seen, true)) {
                    $key = $base.'_'.$i;
                    ++$i;
                }

                $seen[] = $key;
                $col->setKey($key);
            }
        });
    }

    private function normalizeKey(string $input): string
    {
        $key = transliterator_transliterate(
            'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
            $input
        );

        $key = preg_replace('/\s+/', '_', (string) $key);
        $key = preg_replace('/_+/', '_', (string) $key);
        $key = trim((string) $key, '_');

        return (string) $key;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TableField::class,
        ]);
    }
}
