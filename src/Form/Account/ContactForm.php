<?php

namespace App\Form\Account;

use App\Entity\Account\Contact;
use App\Entity\Participant\ParticipantRole;
use App\Repository\ParticipantRoleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'row_attr' => ['class' => 'col-md-2'],
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom...',
                ],
                'required' => true,
            ])
            ->add('function', TextType::class, [
                'row_attr' => ['class' => 'col-md-2'],
                'label' => 'Fonction',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Fonction...',
                ],
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'row_attr' => ['class' => 'col-md-3'],
                'label' => 'Email',
                'required' => true,
            ])
            ->add('participantRoles', EntityType::class, [
                'class' => ParticipantRole::class,
                'choice_label' => 'name',
                'label' => 'Rôles',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'required' => false,
                'query_builder' => function (ParticipantRoleRepository $repo) {
                    return $repo->createQueryBuilder('r')
                        ->andWhere('r.internal = :internal')
                        ->setParameter('internal', false)
                        ->orderBy('r.name', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
