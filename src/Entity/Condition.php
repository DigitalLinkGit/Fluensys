<?php

namespace App\Entity;

use App\Entity\Field\Field;
use App\Repository\ConditionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConditionRepository::class)]
#[ORM\Table(name: '`condition`')]
class Condition
{
    public function __clone()
    {
        $this->id = null;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CaptureElement::class,cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'target_element_id',referencedColumnName: 'id',nullable: false,onDelete: 'RESTRICT'

    )]
    private ?CaptureElement $sourceElement = null;

    #[ORM\ManyToOne(targetEntity: Field::class)]
    #[ORM\JoinColumn(name: 'source_field_id',referencedColumnName: 'id',nullable: false, onDelete: 'RESTRICT')]
    private ?Field $sourceField = null;

    #[ORM\Column(length: 255)]
    private ?string $expectedValue = null;

    #[ORM\ManyToOne(targetEntity: CaptureElement::class,cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'target_element_id',referencedColumnName: 'id',nullable: false,onDelete: 'RESTRICT')]
    private ?CaptureElement $targetElement = null;

    #[ORM\ManyToOne(inversedBy: 'conditions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Capture $capture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceElement(): ?CaptureElement
    {
        return $this->sourceElement;
    }

    public function setSourceElement(?CaptureElement $sourceElement): static
    {
        $this->sourceElement = $sourceElement;

        return $this;
    }

    public function getSourceField(): ?Field
    {
        return $this->sourceField;
    }

    public function setSourceField(?Field $sourceField): static
    {
        $this->sourceField = $sourceField;

        return $this;
    }

    public function getExpectedValue(): ?string
    {
        return $this->expectedValue;
    }

    public function setExpectedValue(string $expectedValue): static
    {
        $this->expectedValue = $expectedValue;

        return $this;
    }

    public function getTargetElement(): ?CaptureElement
    {
        return $this->targetElement;
    }

    public function setTargetElement(?CaptureElement $targetElement): static
    {
        $this->targetElement = $targetElement;

        return $this;
    }

    public function getCapture(): ?Capture
    {
        return $this->capture;
    }

    public function setCapture(?Capture $capture): static
    {
        $this->capture = $capture;

        return $this;
    }

    public function __toString()
    {
        return "(Affiché si : " . $this->getSourceElement()->getName() . " -> " . $this->getSourceField()->getTechnicalName() . " = " . $this->getExpectedValue() . ")";
    }
}
