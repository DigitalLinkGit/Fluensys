<?php

namespace App\Form;

use App\Entity\RenderingConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class RenderingConfigForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('logoFile', FileType::class, [
                'label' => 'Logo',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'accept' => 'image/png,image/jpeg,image/webp,image/svg+xml',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/webp',
                            'image/svg+xml',
                        ],
                        'mimeTypesMessage' => 'Formats acceptés : PNG, JPG, WEBP, SVG',
                    ]),
                ],
            ])
            ->add('documentTitleColor', TextType::class, [
                'label' => 'Couleur du titre du document',
                'required' => false,
                'attr' => [
                    'class' => 'form-control w-auto',
                    'placeholder' => '#112233',
                    'maxlength' => 7,
                    'size' => 7,
                    'style' => 'width: 7ch;',
                    'pattern' => '^#[0-9A-Fa-f]{6}$',
                    'inputmode' => 'text',
                    'autocomplete' => 'off',
                ],
                'constraints' => [
                    new Assert\Length(['max' => 7]),
                    new Assert\Regex([
                        'pattern' => '/^#[0-9A-Fa-f]{6}$/',
                        'message' => 'Format attendu : #RRGGBB',
                    ]),
                ],
            ])
            ->add('titleH1Color', TextType::class, [
                'label' => 'Couleur des titres H1',
                'required' => false,
                'attr' => [
                    'class' => 'form-control w-auto',
                    'placeholder' => '#112233',
                    'maxlength' => 7,
                    'size' => 7,
                    'style' => 'width: 7ch;',
                    'pattern' => '^#[0-9A-Fa-f]{6}$',
                    'inputmode' => 'text',
                    'autocomplete' => 'off',
                ],
                'constraints' => [
                    new Assert\Length(['max' => 7]),
                    new Assert\Regex([
                        'pattern' => '/^#[0-9A-Fa-f]{6}$/',
                        'message' => 'Format attendu : #RRGGBB',
                    ]),
                ],
            ])
            ->add('titleH2Color', TextType::class, [
                'label' => 'Couleur des titres H2',
                'required' => false,
                'attr' => [
                    'class' => 'form-control w-auto',
                    'placeholder' => '#112233',
                    'maxlength' => 7,
                    'size' => 7,
                    'style' => 'width: 7ch;',
                    'pattern' => '^#[0-9A-Fa-f]{6}$',
                    'inputmode' => 'text',
                    'autocomplete' => 'off',
                ],
                'constraints' => [
                    new Assert\Length(['max' => 7]),
                    new Assert\Regex([
                        'pattern' => '/^#[0-9A-Fa-f]{6}$/',
                        'message' => 'Format attendu : #RRGGBB',
                    ]),
                ],
            ])
            ->add('titleH3Color', TextType::class, [
                'label' => 'Couleur des titres H3',
                'required' => false,
                'attr' => [
                    'class' => 'form-control w-auto',
                    'placeholder' => '#112233',
                    'maxlength' => 7,
                    'size' => 7,
                    'style' => 'width: 7ch;',
                    'pattern' => '^#[0-9A-Fa-f]{6}$',
                    'inputmode' => 'text',
                    'autocomplete' => 'off',
                ],
                'constraints' => [
                    new Assert\Length(['max' => 7]),
                    new Assert\Regex([
                        'pattern' => '/^#[0-9A-Fa-f]{6}$/',
                        'message' => 'Format attendu : #RRGGBB',
                    ]),
                ],
            ])
            ->add('tableHeaderBackgroundColor', TextType::class, [
                'label' => 'Couleur de fond des entêtes de tableaux',
                'required' => false,
                'attr' => [
                    'class' => 'form-control w-auto',
                    'placeholder' => '#112233',
                    'maxlength' => 7,
                    'size' => 7,
                    'style' => 'width: 7ch;',
                    'pattern' => '^#[0-9A-Fa-f]{6}$',
                    'inputmode' => 'text',
                    'autocomplete' => 'off',
                ],
                'constraints' => [
                    new Assert\Length(['max' => 7]),
                    new Assert\Regex([
                        'pattern' => '/^#[0-9A-Fa-f]{6}$/',
                        'message' => 'Format attendu : #RRGGBB',
                    ]),
                ],
            ])
            ->add('tableHeaderColor', TextType::class, [
                'label' => 'Couleur de fond des labels des entêtes de tableaux',
                'required' => false,
                'attr' => [
                    'class' => 'form-control w-auto',
                    'placeholder' => '#112233',
                    'maxlength' => 7,
                    'size' => 7,
                    'style' => 'width: 7ch;',
                    'pattern' => '^#[0-9A-Fa-f]{6}$',
                    'inputmode' => 'text',
                    'autocomplete' => 'off',
                ],
                'constraints' => [
                    new Assert\Length(['max' => 7]),
                    new Assert\Regex([
                        'pattern' => '/^#[0-9A-Fa-f]{6}$/',
                        'message' => 'Format attendu : #RRGGBB',
                    ]),
                ],
            ])
            ->add('borderColor', TextType::class, [
                'label' => 'Couleur des bordures',
                'required' => false,
                'attr' => [
                    'class' => 'form-control w-auto',
                    'placeholder' => '#112233',
                    'maxlength' => 7,
                    'size' => 7,
                    'style' => 'width: 7ch;',
                    'pattern' => '^#[0-9A-Fa-f]{6}$',
                    'inputmode' => 'text',
                    'autocomplete' => 'off',
                ],
                'constraints' => [
                    new Assert\Length(['max' => 7]),
                    new Assert\Regex([
                        'pattern' => '/^#[0-9A-Fa-f]{6}$/',
                        'message' => 'Format attendu : #RRGGBB',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RenderingConfig::class,
        ]);
    }
}
