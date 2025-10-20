<?php

namespace App\Form\Capture\Field;

use App\Entity\Capture\Field\FieldConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldConfigForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextareaType::class, [
                'label' => 'Label',
                'required' => true,
                'attr' => ['rows' => 1],
            ])
            ->add('required', CheckboxType::class, [
                'label' => 'Obligatoire',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FieldConfig::class,
        ]);
    }
}
