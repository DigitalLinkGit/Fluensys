<?php

namespace App\Form;

use App\Entity\Capture\Rendering\Chapter;
use App\Entity\Participant\ParticipantRole;
use App\Entity\SystemComponentCaptureElement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SystemComponentCaptureElementForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('respondent', EntityType::class, [
                'class' => ParticipantRole::class,
                'choice_label' => 'id',
            ])
            ->add('responsible', EntityType::class, [
                'class' => ParticipantRole::class,
                'choice_label' => 'id',
            ])
            ->add('validator', EntityType::class, [
                'class' => ParticipantRole::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SystemComponentCaptureElement::class,
        ]);
    }
}
