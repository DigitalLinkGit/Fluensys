<?php

namespace App\Entity;

use App\Repository\CaptureElementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaptureElementRepository::class)]
abstract class CaptureElement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    #[ORM\Column(length: 255)]
    protected ?string $description = null;

    /**
     * @var Collection<int, ParticipantRole>
     */
    #[ORM\ManyToMany(targetEntity: ParticipantRole::class, inversedBy: 'captureElements')]
    protected Collection $participantRoles;

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

    protected ?ChapterRenderInterface $chapterRenderer = null;

    public function __construct()
    {
        $this->participantRoles = new ArrayCollection();
        $this->fields = new ArrayCollection();
        $this->calculatedvariables = new ArrayCollection();
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
     * @return Collection<int, ParticipantRole>
     */
    public function getParticipantRoles(): Collection
    {
        return $this->participantRoles;
    }

    public function addParticipantRole(ParticipantRole $participantRole): static
    {
        if (!$this->participantRoles->contains($participantRole)) {
            $this->participantRoles->add($participantRole);
        }

        return $this;
    }

    public function removeParticipantRole(ParticipantRole $participantRole): static
    {
        $this->participantRoles->removeElement($participantRole);

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

    public function getChapterRenderer(): ?ChapterRenderInterface
    {
        return $this->chapterRenderer;
    }

    public function setChapterRenderer(?ChapterRenderInterface $chapterRenderer): static
    {
        $this->chapterRenderer = $chapterRenderer;

        return $this;
    }

    public function render(): Chapter
    {
        return $this->chapterRenderer->render($this);
    }


}
