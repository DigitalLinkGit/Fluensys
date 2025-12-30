<?php

namespace App\Entity\Capture\CaptureElement;

use App\Entity\Capture\Capture;
use App\Entity\Capture\Field\CalculatedVariable;
use App\Entity\Capture\Field\Field;
use App\Entity\Capture\Rendering\Chapter;
use App\Entity\Participant\ParticipantRole;
use App\Entity\TenantAwareInterface;
use App\Entity\TenantAwareTrait;
use App\Enum\CaptureElementStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'flex' => FlexCaptureElement::class,
    'listable_field' => ListableFieldCaptureElement::class,
])]
abstract class CaptureElement implements TenantAwareInterface
{
    use TenantAwareTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    #[ORM\Column(length: 255)]
    protected ?string $description = null;

    #[ORM\Column(enumType: CaptureElementStatus::class, options: ['default' => CaptureElementStatus::TEMPLATE])]
    private CaptureElementStatus $status = CaptureElementStatus::TEMPLATE;

    #[ORM\OrderBy(['position' => 'ASC'])]
    #[ORM\OneToMany(targetEntity: Field::class, mappedBy: 'captureElement', cascade: ['persist', 'remove'], orphanRemoval: true)]
    protected Collection $fields;

    #[ORM\OneToMany(targetEntity: CalculatedVariable::class, mappedBy: 'captureElement', orphanRemoval: true)]
    protected Collection $calculatedvariables;

    #[ORM\ManyToOne(inversedBy: 'contributorCaptureElements')]
    #[ORM\JoinColumn(nullable: true)]
    private ?ParticipantRole $contributor = null;

    #[ORM\ManyToOne(inversedBy: 'validatorCaptureElements')]
    #[ORM\JoinColumn(nullable: true)]
    private ?ParticipantRole $validator = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private ?bool $template = true;

    #[ORM\OneToOne(inversedBy: 'captureElement', cascade: ['persist', 'remove'])]
    private ?Chapter $chapter = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    protected bool $active = true;

    #[ORM\ManyToOne(
        targetEntity: Capture::class,
        inversedBy: 'captureElements'
    )]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Capture $capture = null;

    #[ORM\Column(type: 'integer')]
    private int $position = 0;

    public function __construct()
    {
        $this->fields = new ArrayCollection();
        $this->calculatedvariables = new ArrayCollection();
    }

    public function __clone()
    {
        $this->id = null;

        // fields
        $newFields = new ArrayCollection();
        foreach ($this->fields as $f) {
            $cloned = clone $f;
            $newFields->add($cloned);
            $cloned->setCaptureElement($this);
        }
        $this->fields = $newFields;

        // chapter
        $clonedChapter = null !== $this->chapter ? clone $this->chapter : null;
        if ($clonedChapter) {
            $clonedChapter->setCaptureElement($this);
        }

        // calculated variables
        $newCvs = new ArrayCollection();
        foreach ($this->calculatedvariables as $cv) {
            $clonedCv = (new CalculatedVariable())
                ->setName((string) $cv->getName())
                ->setTechnicalName((string) $cv->getTechnicalName())
                ->setExpression((string) $cv->getExpression())
                ->setCaptureElement($this);
            $newCvs->add($clonedCv);
        }
        $this->calculatedvariables = $newCvs;

        // template
        $this->template = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
    public function getStatus(): CaptureElementStatus
    {
        return $this->status;
    }

    public function setStatus(CaptureElementStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusLabel(): string
    {
        return $this->status->getLabel();
    }

    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function addField(Field $field): static
    {
        if (!$this->fields->contains($field)) {
            $this->fields->add($field);
            $field->setCaptureElement($this);
            $field->setPosition($this->fields->count());
        }
        return $this;
    }

    public function removeField(Field $field): static
    {
        if ($this->fields->removeElement($field)) {
            if ($field->getCaptureElement() === $this) {
                $field->setCaptureElement(null);
            }
        }
        return $this;
    }

    public function getCalculatedVariables(): Collection
    {
        return $this->calculatedvariables;
    }

    public function addCalculatedVariable(CalculatedVariable $calculatedVariable): static
    {
        if (!$this->calculatedvariables->contains($calculatedVariable)) {
            $this->calculatedvariables->add($calculatedVariable);
            $calculatedVariable->setCaptureElement($this);
        }

        return $this;
    }

    public function removeCalculatedVariable(CalculatedVariable $calculatedVariable): static
    {
        if ($this->calculatedvariables->removeElement($calculatedVariable)) {
            if ($calculatedVariable->getCaptureElement() === $this) {
                $calculatedVariable->setCaptureElement(null);
            }
        }

        return $this;
    }

    public function getContributor(): ?ParticipantRole
    {
        return $this->contributor;
    }

    public function setContributor(?ParticipantRole $contributor): static
    {
        $this->contributor = $contributor;

        return $this;
    }

    public function getValidator(): ?ParticipantRole
    {
        return $this->validator;
    }

    public function setValidator(?ParticipantRole $validator): static
    {
        $this->validator = $validator;

        return $this;
    }

    public function isTemplate(): ?bool
    {
        return $this->template;
    }

    public function setTemplate(bool $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function setChapter(?Chapter $chapter): static
    {
        $this->chapter = $chapter;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getCapture(): ?Capture
    {
        return $this->capture;
    }

    public function setCapture(?Capture $capture): self
    {
        $this->capture = $capture;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
