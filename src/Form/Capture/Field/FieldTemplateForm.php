<?php

namespace App\Form\Capture\Field;

use App\Service\Factory\FieldFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FieldTemplateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('internalPosition', HiddenType::class, [
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Nom du champ'
                ],
            ])
            ->add('externalLabel', TextareaType::class, [
                'label' => 'Label',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Label visible par le répondant',
                    'rows' => 1,
                ],
            ])
            ->add('internalLabel', TextareaType::class, [
                'label' => 'Label',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Label visible par les utilisateurs',
                    'rows' => 1,
                ],
            ])
            ->add('internalRequired')
            ->add('externalRequired')
            ->add('type', HiddenType::class, [
                'mapped' => false,
                'required' => true,
            ]);

        // Registry of subtype options forms
        $registry = [
            'checklist' => ChecklistFieldOptionsTemplateForm::class,
            // 'date' => DateFieldConfigForm::class,
            // 'select' => SelectFieldConfigForm::class,
        ];

        $addSubtypeForm = function (\Symfony\Component\Form\FormInterface $form, ?string $type, ?\App\Entity\Capture\Field\Field $entity) use ($registry) {
            $class = $registry[$type] ?? null;
            if ($class) {
                $form->add('subtype', $class, [
                    'label' => false,
                    'mapped' => false,
                    'data' => $entity,
                ]);
            } else {
                if ($form->has('subtype')) { $form->remove('subtype'); }
            }
        };

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($addSubtypeForm) {
            $form = $event->getForm();
            $data = $event->getData();

            if ($data instanceof \App\Entity\Capture\Field\Field) {
                $type = FieldFactory::getTypeFromInstance($data);
                $form->get('type')->setData($type);
                $addSubtypeForm($form, $type, $data);
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($addSubtypeForm) {
            $form = $event->getForm();
            $submitted = $event->getData() ?? [];
            $type = $submitted['type'] ?? null;
            $addSubtypeForm($form, $type, $form->getData());
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Capture\Field\Field::class,
            'empty_data' => function (FormInterface $form) {
                $type = $form->get('type')->getData() ?? 'textarea'; // fallback
                return FieldFactory::createFromType($type);
            },
        ]);
    }
}
