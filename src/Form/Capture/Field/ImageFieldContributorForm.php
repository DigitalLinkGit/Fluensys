<?php

namespace App\Form\Capture\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ImageFieldContributorForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', FileType::class, [
                'mapped' => false,
                'label' => $options['label'],
                'required' => (bool) $options['required'],
                'constraints' => [new Image(maxSize: '10M')],
                'attr' => ['accept' => 'image/*'],
            ])
            ->add('displayMode', ChoiceType::class, [
                'label' => 'Taille',
                'required' => true,
                'choices' => [
                    'Petit' => 'small',
                    'Moyen' => 'medium',
                    'Grand' => 'large',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'inherit_data' => true,
            'label' => null,
            'required' => false,
        ]);
    }
}
