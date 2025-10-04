<?php

namespace App\Entity\Capture\Field;

use App\Entity\Capture\CaptureElement\CaptureElement;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'textarea' =>TextAreaField::class,
    'text' =>TextField::class,
    'integer' =>IntegerField::class,
    'decimal' =>DecimalField::class,
    'date' =>DateField::class,
    'checklist' => ChecklistField::class,
])]
abstract class Field
{
    public function __clone()
    {
        $this->id = null;
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    protected ?string $externalLabel = null;

    #[ORM\Column(length: 255)]
    protected ?string $internalLabel = null;

    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    #[ORM\Column(length: 255)]
    protected ?string $technicalName = null;

    #[ORM\Column]
    protected ?bool $internalRequired = null;

    #[ORM\Column]
    protected ?bool $externalRequired = null;

    #[ORM\Column]
    protected ?int $internalPosition = null;

    #[ORM\ManyToOne(inversedBy: 'fields')]
    #[ORM\JoinColumn(nullable: false)]
    protected ?CaptureElement $captureElement = null;

    abstract public function getValue(): mixed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExternalLabel(): ?string
    {
        return $this->externalLabel;
    }

    public function setExternalLabel(string $externalLabel): static
    {
        $this->externalLabel = $externalLabel;

        return $this;
    }

    public function getInternalLabel(): ?string
    {
        return $this->internalLabel;
    }

    public function setInternalLabel(string $internalLabel): static
    {
        $this->internalLabel = $internalLabel;

        return $this;
    }

    public function getTechnicalName(): ?string
    {
        return $this->technicalName;
    }

    public function setTechnicalName(string $technicalName): static
    {
        $this->technicalName = $technicalName;

        return $this;
    }

    public function isRequired(): ?bool
    {
        return $this->internalRequired;
    }

    public function setInternalRequired(bool $internalRequired): static
    {
        $this->internalRequired = $internalRequired;

        return $this;
    }

    public function getInternalPosition(): ?int
    {
        return $this->internalPosition;
    }

    public function setInternalPosition(int $internalPosition): static
    {
        $this->internalPosition = $internalPosition;

        return $this;
    }

    public function getCaptureElement(): ?CaptureElement
    {
        return $this->captureElement;
    }

    public function setCaptureElement(?CaptureElement $captureElement): static
    {
        $this->captureElement = $captureElement;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        if (empty($this->technicalName)) {
            $this->technicalName = strtoupper(
                preg_replace('/[^A-Z0-9_]/', '_',
                    transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $name)
                )
            );
        }

        return $this;
    }

    public function isInternalRequired(): ?bool
    {
        return $this->internalRequired;
    }

    public function isExternalRequired(): ?bool
    {
        return $this->externalRequired;
    }

    public function setExternalRequired(bool $externalRequired): static
    {
        $this->externalRequired = $externalRequired;

        return $this;
    }
}
