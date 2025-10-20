<?php

namespace App\Entity\Capture\Field;

use App\Entity\Capture\CaptureElement\CaptureElement;
use App\Service\Helper\FieldTypeHelper;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'textarea' => TextAreaField::class,
    'text' => TextField::class,
    'integer' => IntegerField::class,
    'decimal' => DecimalField::class,
    'date' => DateField::class,
    'checklist' => ChecklistField::class,
    'system_component_collection' => SystemComponentCollectionField::class,
])]
abstract class Field
{
    public function __clone()
    {
        $this->id = null;

        $intConfig = clone $this->internalConfig;
        $this->setInternalConfig($intConfig);
        $intConfig->setFieldUsedAsInternal($this);

        $extConfig = clone $this->externalConfig;
        $this->setExternalConfig($extConfig);
        $extConfig->setFieldUsedAsExternal($this);
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    #[ORM\Column(length: 255)]
    protected ?string $technicalName = null;

    #[ORM\Column]
    protected ?int $position = null;

    #[ORM\ManyToOne(inversedBy: 'fields')]
    #[ORM\JoinColumn(nullable: false)]
    protected ?CaptureElement $captureElement = null;

    #[ORM\OneToOne(targetEntity: FieldConfig::class, inversedBy: 'usedAsExternal', cascade: ['persist', 'remove'], fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'external_config_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?FieldConfig $externalConfig = null;

    #[ORM\OneToOne(targetEntity: FieldConfig::class, inversedBy: 'usedAsInternal', cascade: ['persist', 'remove'], fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'internal_config_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?FieldConfig $internalConfig = null;

    abstract public function getValue(): mixed;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        $this->technicalName = strtoupper(
            preg_replace('/[^A-Z0-9_]/i', '_',
                transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $name)
            )
        );

        return $this;
    }

    public function getInternalConfig(): ?FieldConfig
    {
        return $this->internalConfig;
    }

    public function setInternalConfig(?FieldConfig $internalConfig): static
    {
        $this->internalConfig = $internalConfig;

        return $this;
    }

    public function getExternalConfig(): ?FieldConfig
    {
        return $this->externalConfig;
    }

    public function setExternalConfig(?FieldConfig $externalConfig): static
    {
        $this->externalConfig = $externalConfig;

        return $this;
    }

    public function getType(): ?string
    {
        $helper = new FieldTypeHelper();

        return $helper->getLabelFor($this);
    }
}
