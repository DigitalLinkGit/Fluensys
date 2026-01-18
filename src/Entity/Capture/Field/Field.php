<?php

namespace App\Entity\Capture\Field;

use App\Entity\Capture\CaptureElement\CaptureElement;
use App\Service\Helper\FieldTypeManager;
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
    'url' => UrlField::class,
    'email' => EmailField::class,
    'listable_field' => ListableField::class,
    'table_field' => TableField::class,
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
    protected ?string $name = null;

    #[ORM\Column(length: 255)]
    protected ?string $technicalName = null;

    #[ORM\Column]
    protected ?int $position = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $help = null;

    #[ORM\Column]
    private ?bool $required = null;

    #[ORM\ManyToOne(inversedBy: 'fields')]
    #[ORM\JoinColumn(nullable: false)]
    protected ?CaptureElement $captureElement = null;

    abstract public function getValue(): mixed;

    public function getStringValue(?string $dateFormat = null, ?string $listSeparator = null): string
    {
        $value = $this->getValue();

        if (null === $value) {
            return '';
        }

        $dateFormat ??= 'd/m/Y';
        $listSeparator ??= "\r\n";

        return match (true) {
            $this instanceof DateField => $value instanceof \DateTimeInterface ? $value->format($dateFormat) : '',

            $this instanceof ChecklistField => $this->stringifyChecklist($value, $listSeparator),

            default => $this->stringifyScalar($value),
        };
    }

    private function stringifyChecklist(mixed $value, string $listSeparator): string
    {
        // Storage is array|null, but we tolerate scalar for safety
        if (!is_array($value)) {
            return $this->stringifyScalar($value);
        }

        $values = array_values(array_filter(array_map(
            fn ($v) => $this->stringifyScalar($v),
            $value
        ), fn (string $v) => '' !== $v));

        if ($this instanceof ChecklistField && $this->isUniqueResponse()) {
            return $values[0] ?? '';
        }

        return implode($listSeparator, $values);
    }

    private function stringifyScalar(mixed $value): string
    {
        return trim((string) $value);
    }

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

    public function getType(): ?string
    {
        $helper = new FieldTypeManager();

        return $helper->getLabelFor($this);
    }

    public function getChoices(): array
    {
        return [];
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

    public function setHelp(string $help): static
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
}
