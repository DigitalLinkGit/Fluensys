<?php

namespace App\Form\Capture\CaptureElement;

use App\Entity\Participant\ParticipantRole;
use App\Repository\ParticipantRoleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureElementBaseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de l\'élémént...',
                ],
                'required' => true,
            ])
            ->add('contributor', EntityType::class, [
                'class' => ParticipantRole::class,
                'choice_label' => 'name',
                'label' => 'Contributeur',
                'required' => false,
                'query_builder' => function (ParticipantRoleRepository $r) {
                    return $r->createQueryBuilder('role')
                        ->orderBy('role.name', 'ASC');
                },
                'placeholder' => 'Aucun contribution'
            ])
            ->add('validator', EntityType::class, [
                'class' => ParticipantRole::class,
                'choice_label' => 'name',
                'label' => 'Validateur',
                'required' => false,
                'query_builder' => function (ParticipantRoleRepository $r) {
                    return $r->createQueryBuilder('role')
                        ->orderBy('role.name', 'ASC');
                },
                'placeholder' => 'Aucune validation',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Description de la capture...',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,   // on bosse sur un array
        ]);
    }
}
