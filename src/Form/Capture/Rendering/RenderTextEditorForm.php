<?php

namespace App\Form\Capture\Rendering;

use App\Entity\Capture\Rendering\Chapter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RenderTextEditorForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TitleForm::class, [
                'label' => false,
            ])
            ->add('templateContent', TextareaType::class, [
                'label' => 'Modèle de texte',
                'attr' => ['rows' => 18, 'spellcheck' => 'false', 'class' => 'font-monospace'],
                'required' => false,
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
