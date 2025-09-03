<?php

namespace App\Form\Field;

use App\Entity\Field\ChecklistField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Dedicated config form for ChecklistField. Provides a textarea to edit choices.
 */
class ChecklistFieldTemplateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('choices_raw', TextareaType::class, [
            'label' => 'Choix (1 par ligne)',
            'mapped' => false,
            'required' => true,
            'attr' => [
                'rows' => 6,
                'placeholder' => "Option A\nOption B\nOption C",
            ],
        ]);

        // Initialize textarea from existing choices
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            if (!$data instanceof ChecklistField) {
                return;
            }
            $lines = [];
            foreach ($data->getChoices() as $c) {
                $label = $c['label'] ?? '';
                if ($label === '') { continue; }
                $lines[] = $label;
            }
            $event->getForm()->get('choices_raw')->setData(implode("\n", $lines));
        });

        // On submit, parse textarea into structured choices and clean value
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $entity = $event->getData();
            $form = $event->getForm();
            if (!$entity instanceof ChecklistField) { return; }
            $raw = (string) $form->get('choices_raw')->getData();
            $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $raw)), fn($l) => $l !== ''));
            $choices = [];
            foreach ($lines as $line) {
                $label = $line;
                if ($label === '') { continue; }
                $choices[] = ['label' => $label, 'value' => $label];
            }
            $entity->setChoices($choices);
            // Clean existing selected values to keep only valid ones
            $validValues = array_column($choices, 'value');
            $current = (array) ($entity->getValue() ?? []);
            $entity->setValue(array_values(array_intersect($current, $validValues)) ?: null);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChecklistField::class,
        ]);
    }
}
