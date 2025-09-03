<?php

namespace App\Form\Rendering;

use App\Dto\RenderTextDto;
use App\Entity\Chapter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RenderTextEditorForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du chapitre',
                'required' => false,
            ])
            ->add('level', IntegerType::class, [
                'label' => 'Niveau',
                'empty_data' => '1',
            ])
            ->add('templateContent', TextareaType::class, [
                'label' => 'Modèle de texte',
                'attr' => ['rows' => 18, 'spellcheck' => 'false', 'class' => 'font-monospace'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chapter::class,
            'variables' => [],
        ]);
    }
}
