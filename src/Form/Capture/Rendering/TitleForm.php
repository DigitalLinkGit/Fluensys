<?php

namespace App\Form\Capture\Rendering;

use App\Entity\Capture\Rendering\Title;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TitleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextType::class, [
                'label' => 'Titre',
                'required' => false,
            ])
            ->add('level', ChoiceType::class, [
                'label' => 'Niveau',
                'choices' => [
                    'T1' => 1,
                    'T2' => 2,
                    'T3' => 3,
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'class' => 'w-auto',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Title::class,
        ]);
    }
}
