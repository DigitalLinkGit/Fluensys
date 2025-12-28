<?php

namespace App\Form\Participant;

use App\Entity\Participant\ParticipantRole;
use App\Entity\Participant\User;
use App\Repository\ParticipantRoleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => array_filter([
                    $options['is_edit'] ? null : new NotBlank(message: 'Le mot de passe est obligatoire.'),
                    new Length(min: 8, minMessage: 'Au moins {{ limit }} caractères.'),
                ]),
            ])
            ->add('username')
            ->add('function')
            ->add('participantRoles', EntityType::class, [
                'class' => ParticipantRole::class,
                'choice_label' => 'name',
                'label' => false,
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'required' => false,
                'query_builder' => function (ParticipantRoleRepository $repo) {
                    return $repo->createQueryBuilder('r')
                        ->andWhere('r.internal = :internal')
                        ->setParameter('internal', true)
                        ->orderBy('r.name', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
        ]);
    }
}
