<?php

namespace App\Form\Account;

use App\Entity\Account\SystemComponent;
use App\Enum\SystemComponentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SystemComponentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'required' => true,
                'row_attr' => ['class' => 'col-md-4'],

            ])
            ->add('type', ChoiceType::class, [
                'label' => false,
                'choices' => array_combine(
                    array_map(fn($c) => ucfirst($c->name), SystemComponentType::cases()),
                    SystemComponentType::cases()
                ),
                'choice_label' => fn(SystemComponentType $choice) => ucfirst($choice->value),
                'required' => true,
                'row_attr' => ['class' => 'col-md-4'],
            ]);
        ;



    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SystemComponent::class,
        ]);
    }
}
