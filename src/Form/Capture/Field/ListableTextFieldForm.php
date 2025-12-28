<?php

// src/Form/Capture/Field/TextFieldForm.php
namespace App\Form\Capture\Field;

use App\Entity\Capture\Field\ListableTextField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ListableTextFieldForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('value', TextType::class, [
            'label' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ListableTextField::class,
        ]);
    }
}
