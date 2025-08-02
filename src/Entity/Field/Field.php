<?php

namespace App\Entity\Field;

use App\Entity\CaptureElement;
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
])]
abstract class Field
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    protected ?string $externalLabel = null;

    #[ORM\Column(length: 255)]
    protected ?string $internalLabel = null;

    #[ORM\Column(length: 255)]
    protected ?string $technicalName = null;

    #[ORM\Column]
    protected ?bool $required = null;

    #[ORM\Column]
    protected ?int $position = null;

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

        if (empty($this->technicalName)) {
            $this->technicalName = strtoupper(
                preg_replace('/[^A-Z0-9_]/', '_',
                    transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $internalLabel)
                )
            );
        }

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
        return $this->required;
    }

    public function setRequired(bool $required): static
    {
        $this->required = $required;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

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
}
