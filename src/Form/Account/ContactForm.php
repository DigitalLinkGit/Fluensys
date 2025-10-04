<?php

namespace App\Form\Account;

use App\Entity\Account\Contact;
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
                'row_attr' => ['class' => 'col-md-4'],
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom...',
                ],
                'required' => true,
            ])
            ->add('function', TextType::class, [
                'row_attr' => ['class' => 'col-md-3'],
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Fonction...',
                ],
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'row_attr' => ['class' => 'col-md-4'],
                'label' => false,
                'required' => true,
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
