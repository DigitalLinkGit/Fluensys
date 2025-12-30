<?php

namespace App\Form\Capture\Field;

use App\Entity\Capture\Field\ListableFieldTextItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ListableFieldItemContributorForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('value', TextType::class, [
            'label' => false,
            'required' => false,
            'attr' => ['rows' => 1],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ListableFieldTextItem::class,
        ]);
    }
}
