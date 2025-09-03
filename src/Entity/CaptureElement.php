<?php

namespace App\Entity;

use App\Entity\Field\Field;
use App\Entity\Rendering\CalculatedVariable;
use App\Entity\Rendering\ChapterRenderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'flex' => FlexCaptureElement::class,
])]
abstract class CaptureElement
{
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
        // calculated variables
        $newCvs = new ArrayCollection();
        foreach ($this->calculatedvariables as $cv) {
            $clonedCv = (new CalculatedVariable())
                ->setName((string)$cv->getName())
                ->setTechnicalName((string)$cv->getTechnicalName())
                ->setExpression((string)$cv->getExpression())
                ->setCaptureElement($this);
            $newCvs->add($clonedCv);
        }
        $this->calculatedvariables = $newCvs;
        $this->template = false;
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    #[ORM\Column(length: 255)]
    protected ?string $description = null;

    /**
     * @var Collection<int, Field>
     */
    #[ORM\OneToMany(targetEntity: Field::class, mappedBy: 'captureElement', orphanRemoval: true)]
    protected Collection $fields;

    /**
     * @var Collection<int, CalculatedVariable>
     */
    #[ORM\OneToMany(targetEntity: CalculatedVariable::class, mappedBy: 'captureElement', orphanRemoval: true)]
    protected Collection $calculatedvariables;


    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ParticipantRole $respondent = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ParticipantRole $responsible = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ParticipantRole $validator = null;

    #[ORM\Column]
    private ?bool $template = null;

    #[ORM\OneToOne(inversedBy: 'captureElement', cascade: ['persist', 'remove'])]
    private ?Chapter $chapter = null;

    public function __construct()
    {
        $this->fields = new ArrayCollection();
        $this->calculatedvariables = new ArrayCollection();
        $this->template = true;
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

    /**
     * @return Collection<int, Field>
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function addField(Field $field): static
    {
        if (!$this->fields->contains($field)) {
            $this->fields->add($field);
            $field->setCaptureElement($this);
        }

        return $this;
    }

    public function removeField(Field $field): static
    {
        if ($this->fields->removeElement($field)) {
            // set the owning side to null (unless already changed)
            if ($field->getCaptureElement() === $this) {
                $field->setCaptureElement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CalculatedVariable>
     */
    public function getCalculatedvariables(): Collection
    {
        return $this->calculatedvariables;
    }

    public function addCalculatedvariable(CalculatedVariable $calculatedvariable): static
    {
        if (!$this->calculatedvariables->contains($calculatedvariable)) {
            $this->calculatedvariables->add($calculatedvariable);
            $calculatedvariable->setCaptureElement($this);
        }

        return $this;
    }

    public function removeCalculatedvariable(CalculatedVariable $calculatedvariable): static
    {
        if ($this->calculatedvariables->removeElement($calculatedvariable)) {
            // set the owning side to null (unless already changed)
            if ($calculatedvariable->getCaptureElement() === $this) {
                $calculatedvariable->setCaptureElement(null);
            }
        }

        return $this;
    }

    public function getRespondent(): ?ParticipantRole
    {
        return $this->respondent;
    }

    public function setRespondent(?ParticipantRole $respondent): static
    {
        $this->respondent = $respondent;

        return $this;
    }

    public function getResponsible(): ?ParticipantRole
    {
        return $this->responsible;
    }

    public function setResponsible(?ParticipantRole $responsible): static
    {
        $this->responsible = $responsible;

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
}
