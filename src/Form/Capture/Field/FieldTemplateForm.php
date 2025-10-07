<?php

namespace App\Form\Capture\Field;

use App\Entity\Capture\Field\Field;
use App\Service\Helper\FieldTypeHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class FieldTemplateForm extends AbstractType
{
    public function __construct(private readonly FieldTypeHelper $typeHelper) {}

    /** @var array<string, class-string> */
    private array $subtypeRegistry = [
        'checklist' => ChecklistFieldOptionsTemplateForm::class,
        // 'date' => DateFieldOptionsTemplateForm::class,
        // 'select' => SelectFieldOptionsTemplateForm::class,
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('internalPosition', HiddenType::class, ['required' => false])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => ['placeholder' => 'Nom du champ'],
            ])
            ->add('externalLabel', TextareaType::class, [
                'label' => 'Label',
                'required' => true,
                'attr' => ['placeholder' => 'Label visible par le répondant', 'rows' => 1],
            ])
            ->add('internalLabel', TextareaType::class, [
                'label' => 'Label',
                'required' => true,
                'attr' => ['placeholder' => 'Label visible par les utilisateurs', 'rows' => 1],
            ])
            ->add('internalRequired')
            ->add('externalRequired')
            // carries the drag-and-drop discriminator; never shown/edited by user
            ->add('type', HiddenType::class, ['mapped' => false, 'required' => true]);

        // Create the sub type instance just-in-time at submit.
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
            $form = $event->getForm();
            if ($form->getData() instanceof Field) {
                return; // edit case: already concrete
            }

            $submitted = $event->getData() ?? [];
            $typeKey = $submitted['type'] ?? null;
            if (!$typeKey) {
                throw new \LogicException('Missing field "type" for Field creation.');
            }

            $class = $this->typeHelper->resolveClass($typeKey);
            /** @var Field $concrete */
            $concrete = new $class();
            $form->setData($concrete);
        });

        // inject subtype options form (edit/new with prototype that includes hidden type).
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event): void {
            $form = $event->getForm();
            $data = $event->getData();
            if (!$data instanceof Field) {
                return;
            }
            $key = $this->typeHelper->getKeyFor($data);
            if ($form->has('type')) {
                $form->get('type')->setData($key);
            }
            $class = $this->subtypeRegistry[$key] ?? null;
            if ($class) {
                $form->add('subtype', $class, [
                    'label' => false,
                    'mapped' => false,
                    'data' => $data,
                ]);
            } elseif ($form->has('subtype')) {
                $form->remove('subtype');
            }
        });
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['type_key'] = null;
        $view->vars['type_label'] = null;

        $data = $form->getData();
        if ($data instanceof Field) {
            $key = $this->typeHelper->getKeyFor($data);
            $view->vars['type_key'] = $key;
            $view->vars['type_label'] = $this->typeHelper->getLabelForKey($key);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Field::class,
            'empty_data' => function (FormInterface $form) {
                $obj = $form->getData();
                if ($obj instanceof Field) {
                    return $obj;
                }
                //if PRE_SUBMIT did not create the instance, fail explicitly.
                throw new \LogicException('Concrete Field must be created at PRE_SUBMIT based on hidden "type".');
            },
        ]);
    }
}
