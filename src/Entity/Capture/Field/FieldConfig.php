<?php

namespace App\Entity\Capture\Field;

use App\Repository\FieldConfigRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FieldConfigRepository::class)]
class FieldConfig
{
    public function __clone()
    {
        $this->id = null;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $help = null;

    #[ORM\Column]
    private ?bool $required = null;

    #[ORM\OneToOne(targetEntity: Field::class, mappedBy: 'externalConfig')]
    private ?Field $usedAsExternal = null;

    #[ORM\OneToOne(targetEntity: Field::class, mappedBy: 'internalConfig')]
    private ?Field $usedAsInternal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getHelp(): ?string
    {
        return $this->help;
    }

    public function setHelp(?string $help): static
    {
        $this->help = $help;

        return $this;
    }

    public function isRequired(): ?bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): static
    {
        $this->required = $required;

        return $this;
    }

    public function getFieldUsedAsExternal(): ?Field
    {
        return $this->usedAsExternal;
    }

    public function setFieldUsedAsExternal(?Field $field): static
    {
        if (null === $field && null !== $this->usedAsExternal) {
            $this->usedAsExternal->setExternalConfig(null);
        }

        if (null !== $field && $field->getExternalConfig() !== $this) {
            $field->setExternalConfig($this);
        }

        $this->field = $field;

        return $this;
    }

    public function getFieldUsedAsInternal(): ?Field
    {
        return $this->usedAsInternal;
    }

    public function setFieldUsedAsInternal(?Field $field): static
    {
        if (null === $field && null !== $this->usedAsInternal) {
            $this->usedAsInternal->setInternalConfig(null);
        }

        if (null !== $field && $field->getInternalConfig() !== $this) {
            $field->setInternalConfig($this);
        }

        $this->field = $field;

        return $this;
    }
}
