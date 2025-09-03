<?php

namespace App\Form\Field;

use App\Entity\Field\Field;
use App\Factory\FieldFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use App\Form\Field\ChecklistFieldConfigForm;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ConfigFieldForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('position', HiddenType::class, [
                'required' => false,
            ])
            ->add('externalLabel', TextareaType::class, [
                'label' => 'Label',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Label visible par le répondant'
                ],
            ])
            ->add('internalLabel', TextType::class, [
                'label' => 'Label interne',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Label visible par les utilisateurs'
                ],
            ])
            ->add('required')
            ->add('type', HiddenType::class, [
                'mapped' => false,
                'required' => true,
            ]);

        $addSubtypeForm = function (\Symfony\Component\Form\FormInterface $form, ?string $type, ?Field $entity) {
            // Add/remove dedicated config subform when needed
            if ($type === 'checklist') {
                $form->add('subtype', ChecklistFieldConfigForm::class, [
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

            if ($data instanceof Field) {
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
            'data_class' => Field::class,
            'empty_data' => function (FormInterface $form) {
                $type = $form->get('type')->getData() ?? 'textarea'; // fallback
                return FieldFactory::createFromType($type);
            },
        ]);
    }
}
