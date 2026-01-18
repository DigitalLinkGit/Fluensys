<?php

namespace App\Form\Capture\Field;

use App\Entity\Capture\Field\TableFieldColumn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TableFieldColumnTemplateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Libellé',
                'required' => true,
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'required' => true,
                'choices' => [
                    'Texte' => 'text',
                    'Entier' => 'integer',
                    'Date' => 'date',
                ],
            ])
            ->add('key', HiddenType::class, [
                'label' => 'Clé (optionnel)',
                'required' => false,
                'help' => 'Si vide, elle sera générée à partir du libellé.',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TableFieldColumn::class,
        ]);
    }
}
