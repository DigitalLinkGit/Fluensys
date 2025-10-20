<?php

namespace App\Form\Capture;

use App\Entity\Capture\Capture;
use App\Form\Capture\CaptureElement\CaptureElementMinimalForm;
use App\Form\Capture\Rendering\TitleForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CaptureTemplateForm extends AbstractType
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nom de la capture...'],
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Description de la capture...'],
            ])
            ->add('title', TitleForm::class, ['label' => false])
            ->add('captureElements', CollectionType::class, [
                'entry_type' => CaptureElementMinimalForm::class,
                'allow_add' => true,
                'disabled' => false,
                'allow_delete' => true,
                'label' => false,
                'by_reference' => false,
                'prototype' => true,
                'entry_options' => ['label' => false],
                'attr' => ['data-controller' => 'capture'],
            ])
            ->add('conditions', CollectionType::class, [
                'entry_type' => ConditionForm::class,
                'allow_add' => true,
                'disabled' => false,
                'allow_delete' => true,
                'label' => false,
                'by_reference' => false,
                'prototype' => true,
                'entry_options' => [
                    'label' => false,
                    'capture_elements' => ($options['data'] && method_exists($options['data'], 'getCaptureElements'))
                        ? $options['data']->getCaptureElements()
                        : [],
                ],
                'attr' => [
                    'data-controller' => 'condition',
                    'data-condition-fields-url-value' => $this->urlGenerator->generate('condition_fields'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Capture::class]);
    }
}
