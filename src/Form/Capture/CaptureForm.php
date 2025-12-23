<?php

namespace App\Form\Capture;

use App\Entity\Account\Account;
use App\Entity\Capture\Capture;
use App\Entity\Participant\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de la capture_template...',
                ],
                'required' => true,
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
            ->add('responsible', EntityType::class, [
                'class' => User::class,
                'choice_label' => fn (User $u) => $u->getUsername(),
                'placeholder' => 'Sélectionner un responsable',
                'required' => true,
            ])
            ->add('account', EntityType::class, [
                'class' => Account::class,
                'choice_label' => 'name',
                'mapped' => true,
                'required' => true,
                'placeholder' => '— Sélectionner un compte —',
                'data' => $builder->getData()?->getAccount(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Capture::class,
        ]);
    }
}
