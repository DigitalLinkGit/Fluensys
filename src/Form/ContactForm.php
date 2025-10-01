<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Contact;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'row_attr' => ['class' => 'col-md-4'],
                'label' => false,
            ])
            ->add('name', null, [
                'row_attr' => ['class' => 'col-md-4'],
                'label' => false,
            ])
            ->add('function', null, [
                'row_attr' => ['class' => 'col-md-3'],
                'label' => false,
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
