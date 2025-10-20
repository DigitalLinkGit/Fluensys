<?php

namespace App\Form\Capture;

use App\Entity\Capture\CaptureElement\CaptureElement;
use App\Entity\Capture\Condition;
use App\Entity\Capture\Field\Field;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ConditionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('expectedValue', TextType::class, [
            'label' => false,
            'attr' => ['class' => 'form-control', 'placeholder' => 'Saisir la valeur attendue...'],
            'required' => true,
        ]);

        $elements = $this->getElementsFromOptions($options);

        $builder->add('targetElement', EntityType::class, [
            'class' => CaptureElement::class,
            'choice_label' => 'name',
            'placeholder' => 'Sélectionner un élément...',
            'label' => false,
            'choices' => $this->buildTargetChoices($elements),
            'attr' => [
                'data-action' => 'change->condition#onTargetChange',
                'autocomplete' => 'off',
            ],
        ]);

        $builder->add('sourceElement', EntityType::class, [
            'class' => CaptureElement::class,
            'choice_label' => 'name',
            'placeholder' => 'Sélectionner un élément...',
            'label' => false,
            'choices' => $this->buildTargetChoices($elements),
            'attr' => [
                'data-action' => 'change->condition#onSourceChange',
                'autocomplete' => 'off',
            ],
        ]);

        $builder->add('sourceField', EntityType::class, [
            'class' => Field::class,
            'choice_label' => 'technicalName',
            'placeholder' => 'Sélectionner un champ...',
            'label' => false,
            'choices' => [],
            'required' => true,
            'attr' => [
                'autocomplete' => 'off',
            ],
        ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (PostSubmitEvent $event) {
            /** @var Condition $cond */
            $cond = $event->getData();
            $form = $event->getForm();
            if (!$cond) {
                return;
            }

            $src = $cond->getSourceElement();
            $tgt = $cond->getTargetElement();
            $fld = $cond->getSourceField();

            // source != target
            if ($src && $tgt && $src->getId() === $tgt->getId()) {
                $form->get('sourceElement')->addError(new FormError('La source doit être différente de la cible.'));
            }

            // field in sourceElement
            if ($src && $fld && method_exists($src, 'getFields')) {
                $fields = $src->getFields();
                $contains = method_exists($fields, 'contains')
                    ? $fields->contains($fld)
                    : in_array($fld, is_iterable($fields) ? iterator_to_array($fields) : (array) $fields, true);

                if (!$contains) {
                    $form->get('sourceField')->addError(new FormError('Le champ doit appartenir à la source sélectionnée.'));
                }
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (PreSubmitEvent $event) use ($options) {
            $data = $event->getData() ?? [];
            $form = $event->getForm();

            $sourceId = $data['sourceElement'] ?? null;
            if (!$sourceId) {
                return;
            }

            $source = null;
            foreach ($this->getElementsFromOptions($options) as $el) {
                if ((string) $el->getId() === (string) $sourceId) {
                    $source = $el;
                    break;
                }
            }
            if (!$source || !method_exists($source, 'getFields')) {
                return;
            }

            $choices = [];
            foreach ($source->getFields() as $f) {
                $choices[] = $f;
            }

            $form->add('sourceField', EntityType::class, [
                'class' => Field::class,
                'choice_label' => 'technicalName',
                'placeholder' => 'Sélectionner un champ...',
                'label' => false,
                'choices' => $choices,
                'disabled' => empty($choices),
                'required' => true,
            ]);
        });

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            /** @var Condition|null $cond */
            $cond = $event->getData();
            if (!$cond) {
                return;
            }

            $form = $event->getForm();
            $elementsAll = $this->getElementsFromOptions($options);

            $src = $cond->getSourceElement();
            $tgt = $cond->getTargetElement();
            $currentField = $cond->getSourceField();

            $baseChoices = $this->buildTargetChoices($elementsAll);

            $form->add('sourceElement', EntityType::class, [
                'class' => CaptureElement::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'placeholder' => 'Sélectionner un élément...',
                'label' => false,
                'choices' => $baseChoices,
                'attr' => ['data-action' => 'change->condition#onSourceChange'],
                'data' => $src,
            ]);

            $form->add('targetElement', EntityType::class, [
                'class' => CaptureElement::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'placeholder' => 'Sélectionner un élément...',
                'label' => false,
                'choices' => $baseChoices,
                'attr' => ['data-action' => 'change->condition#onTargetChange'],
                'data' => $tgt,
            ]);

            $fieldChoices = [];
            if ($src && method_exists($src, 'getFields')) {
                foreach ($src->getFields() as $f) {
                    $fieldChoices[] = $f;
                }
            }

            $form->add('sourceField', EntityType::class, [
                'class' => Field::class,
                'choice_label' => 'technicalName',
                'choice_value' => 'id',
                'placeholder' => 'Sélectionner un champ...',
                'label' => false,
                'choices' => $fieldChoices,
                'disabled' => empty($fieldChoices),
                'required' => true,
                'data' => $currentField,
            ]);
        });
    }

    private function ensureIncluded(array $choices, ?object $value = null): array
    {
        if (!$value) {
            return $choices;
        }
        foreach ($choices as $c) {
            if ($c === $value) {
                return $choices;
            }
            if (method_exists($c, 'getId') && method_exists($value, 'getId') && (string) $c->getId() === (string) $value->getId()) {
                return $choices;
            }
        }
        $choices[] = $value; // inclure la valeur courante même si hors filtre

        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Condition::class,
            'capture_elements' => null, // iterable<CaptureElement> — fourni par le parent
        ]);
        $resolver->setRequired(['capture_elements']);
    }

    private function getElementsFromOptions(array $options): array
    {
        $root = $options['capture_elements'] ?? [];
        // normaliser en tableau
        if ($root instanceof \Traversable) {
            $root = iterator_to_array($root);
        } elseif (!is_array($root)) {
            $root = (array) $root;
        }
        // aplatir récursif (Collection/Traversable/tableaux imbriqués)
        $flat = [];
        $stack = $root;
        while (!empty($stack)) {
            $item = array_pop($stack);
            if ($item instanceof \Doctrine\Common\Collections\Collection || $item instanceof \Traversable) {
                foreach ($item as $sub) {
                    $stack[] = $sub;
                }
                continue;
            }
            if (is_array($item)) {
                foreach ($item as $sub) {
                    $stack[] = $sub;
                }
                continue;
            }
            if ($item instanceof CaptureElement) {
                $flat[] = $item;
            }
        }

        return $flat;
    }

    private function buildTargetChoices(array $elements): array
    {
        // STRICT : seulement isTemplate = true
        return array_values(array_filter(
            $elements,
            fn (CaptureElement $e) => true === $e->isTemplate()
        ));
    }
}
