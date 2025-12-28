<?php

namespace App\Form\Capture;

use App\Entity\Account\Account;
use App\Entity\Capture\Capture;
use App\Entity\Participant\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureNewForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('account', EntityType::class, [
                'class' => Account::class,
                'choice_label' => 'name',
                'mapped' => false,
                'required' => true,
                'placeholder' => '— Sélectionner un compte —',
                'attr' => [
                    'class' => 'w-auto',
                ],
            ])

            ->add('template', EntityType::class, [
                'class' => Capture::class,
                'choice_label' => 'name',
                'mapped' => false,
                'required' => true,
                'placeholder' => '— Sélectionner un template —',
                'query_builder' => fn (EntityRepository $er) => $er->createQueryBuilder('c')
                    ->andWhere('c.template = true')
                    ->orderBy('c.name', 'ASC'),
                'attr' => [
                    'class' => 'w-auto',
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de la capture_template...',
                ],
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Description de la capture_template...',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
