<?php

namespace App\Form\Capture\CaptureElement;

use App\Entity\Capture\CaptureElement\CaptureElement;
use App\Entity\Capture\CaptureElement\ListableFieldCaptureElement;
use App\Entity\Capture\Field\ListableField;
use App\Form\Capture\Field\FieldTemplateForm;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureElementTemplateForm extends CaptureElementBaseForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var CaptureElement $element */
        $element = $builder->getData();
        $isListableElement = $element instanceof ListableFieldCaptureElement;

        parent::buildForm($builder, $options);

        $builder->add('type', HiddenType::class, [
            'mapped' => false,
            'required' => true,
        ]);

        $builder->add('fields', CollectionType::class, [
            'entry_type' => FieldTemplateForm::class,
            'allow_add' => !$isListableElement,
            'allow_delete' => !$isListableElement,
            'label' => false,
            'by_reference' => false,
            'prototype' => true,
            'entry_options' => ['label' => false],
            'attr' => [
                'data-controller' => 'capture-element',
            ],
        ]);

        // Ensure exactly 1 ListableField exists on edit/display
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event): void {
            $element = $event->getData();
            if (!$element instanceof ListableFieldCaptureElement) {
                return;
            }

            if ($element->getFields()->isEmpty()) {
                $element->addField(new ListableField());
            }
        });

        // Force submit payload: keep only first field and force type=listable
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
            $form = $event->getForm();
            $element = $form->getData();

            if (!$element instanceof ListableFieldCaptureElement) {
                return;
            }

            $data = $event->getData() ?? [];
            $fields = $data['fields'] ?? [];

            // normalize to one entry
            $first = is_array($fields) && isset($fields[0]) && is_array($fields[0]) ? $fields[0] : [];
            $first['type'] = 'listable';

            $data['fields'] = [$first];
            $event->setData($data);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CaptureElement::class,
        ]);
    }
}
