<?php

namespace App\Form\Capture\CaptureElement;

use App\Entity\Capture\CaptureElement\CaptureElement;
use App\Form\Capture\Field\FieldForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureElementRespondForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fields', CollectionType::class, [
                'entry_type' => FieldForm::class,
                'label' => false,
                'entry_options' => [
                    'label' => false,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CaptureElement::class,
        ]);
    }
}
