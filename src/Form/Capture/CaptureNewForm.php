<?php

namespace App\Form\Capture;

use App\Entity\Account\Account;
use App\Entity\Capture\Capture;
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
            ])

            ->add('name', TextType::class, ['mapped' => false, 'required' => false])
            ->add('description', TextareaType::class, ['mapped' => false, 'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
