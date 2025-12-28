<?php

namespace App\Service\Helper;

use App\Entity\Capture\Field\ChecklistField;
use App\Entity\Capture\Field\DateField;
use App\Entity\Capture\Field\DecimalField;
use App\Entity\Capture\Field\EmailField;
use App\Entity\Capture\Field\Field;
use App\Entity\Capture\Field\IntegerField;
use App\Entity\Capture\Field\SystemComponentCollectionField;
use App\Entity\Capture\Field\TextAreaField;
use App\Entity\Capture\Field\TextField;
use App\Entity\Capture\Field\TextListField;
use App\Entity\Capture\Field\UrlField;
use App\Form\Capture\Field\SystemComponentCollectionFieldForm;
use App\Form\Capture\Field\TextListFieldForm;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

final class FieldTypeManager
{
    /** key (discriminator) => FQCN */
    private array $map = [
        'textarea' => TextAreaField::class,
        'text' => TextField::class,
        'integer' => IntegerField::class,
        'decimal' => DecimalField::class,
        'date' => DateField::class,
        'checklist' => ChecklistField::class,
        'url' => UrlField::class,
        'email' => EmailField::class,
        'text-list' => TextListField::class,
        'system_component_collection' => SystemComponentCollectionField::class,
    ];

    /** key => FormType */
    private array $formTypes = [
        'textarea' => TextareaType::class,
        'text' => TextType::class,
        'integer' => IntegerType::class,
        'decimal' => TextType::class,
        'date' => DateType::class,
        'checklist' => ChoiceType::class,
        'url' => UrlType::class,
        'email' => EmailType::class,
        'text-list' => TextListFieldForm::class,
        'system_component_collection' => SystemComponentCollectionFieldForm::class,
    ];

    /** key => label */
    private array $labels = [
        'textarea' => 'Texte long',
        'text' => 'Texte court',
        'integer' => 'Nombre entier',
        'decimal' => 'Nombre décimal',
        'date' => 'Date',
        'checklist' => 'Choix multiples',
        'url' => 'lien',
        'email' => 'Email',
        'text-list' => 'Liste',
        'system_component_collection' => 'Composants de SI',
    ];

    /** key => public (bool) */
    private array $public = [
        'textarea' => true,
        'text' => true,
        'integer' => true,
        'decimal' => true,
        'date' => true,
        'checklist' => true,
        'url' => true,
        'email' => true,
        'text-list' => true,
        'system_component_collection' => false,
    ];

    /** FQCN form key */
    public function resolveClass(string $key): string
    {
        if (!isset($this->map[$key])) {
            throw new \InvalidArgumentException("Type Field inconnu: {$key}");
        }

        return $this->map[$key];
    }

    /** discriminator from instance */
    public function getKeyFor(Field $field): string
    {
        $fqcn = $field::class;
        foreach ($this->map as $key => $class) {
            if ($fqcn === $class || is_subclass_of($fqcn, $class)) {
                return $key;
            }
        }
        throw new \RuntimeException('Aucune clé trouvée pour '.$fqcn);
    }

    /** Label from key */
    public function getLabelForKey(string $key): string
    {
        return $this->labels[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }

    /** Form type from instance */
    public function getFormTypeFor(Field $field): string
    {
        return $this->formTypes[$this->getKeyFor($field)];
    }

    /** Label from instance */
    public function getLabelFor(Field $field): string
    {
        return $this->getLabelForKey($this->getKeyFor($field));
    }

    /** Returns true if the key is public (draggable in library). */
    public function isPublicKey(string $key): bool
    {
        return $this->public[$key] ?? false;
    }

    /** Returns the public discriminator keys preserving declaration order. */
    public function getPublicKeys(): array
    {
        $keys = [];
        foreach ($this->map as $key => $_class) {
            if ($this->isPublicKey($key)) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    /**
     * Returns items for the draggable library.
     * When $onlyPublic is true, only public keys are included.
     * Shape: [ ['key' => 'textarea', 'label' => 'Texte long'], ... ].
     */
    public function getLibraryChoices(bool $onlyPublic = true): array
    {
        $items = [];
        foreach ($this->map as $key => $_class) {
            if ($onlyPublic && !$this->isPublicKey($key)) {
                continue;
            }
            $items[] = [
                'key' => $key,
                'label' => $this->getLabelForKey($key),
            ];
        }

        return $items;
    }

    /** allowed keys list */
    public function getAllowedKeys(): array
    {
        return array_keys($this->map);
    }
}
