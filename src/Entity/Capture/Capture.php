<?php

namespace App\Entity\Capture;

use App\Entity\Capture\CaptureElement\CaptureElement;
use App\Entity\Capture\Rendering\Title;
use App\Entity\Participant\ParticipantRole;
use App\Repository\CaptureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaptureRepository::class)]
class Capture
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, CaptureElement>
     */
    #[ORM\ManyToMany(targetEntity: CaptureElement::class)]
    private Collection $captureElements;


    #[ORM\Column]
    private ?bool $template = null;

    #[ORM\OneToOne(targetEntity: Title::class, cascade: ['persist', 'remove'],fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Title $title = null;

    /**
     * @var Collection<int, Condition>
     */
    #[ORM\OneToMany(targetEntity: Condition::class, mappedBy: 'capture', cascade: ['persist','remove'], orphanRemoval: true)]
    private Collection $conditions;

    public function __construct()
    {
        $this->captureElements = new ArrayCollection();
        $this->template = true;
        $this->conditions = new ArrayCollection();
    }

    public function __clone()
    {
        $this->id = null;

        // 1) Cloner éléments + maps
        $elementMap = [];
        $fieldMapByElement = [];
        $originalElements = $this->captureElements;
        $this->captureElements = new ArrayCollection();

        foreach ($originalElements as $el) {
            $cl = clone $el;
            $cl->setTemplate(false);
            $this->captureElements->add($cl);

            $elementMap[$el->getId()] = $cl;

            // map fields (en supposant même ordre)
            $origFields   = $el->getFields()->toArray();
            $clonedFields = $cl->getFields()->toArray();
            $fm = [];
            foreach ($origFields as $i => $of) {
                $fm[$of->getId()] = $clonedFields[$i]; // sinon mappe par name si besoin
            }
            $fieldMapByElement[$el->getId()] = $fm;
        }

        // 2) Cloner conditions + rewire éléments & fields
        $originalConditions = $this->conditions;
        $this->conditions = new ArrayCollection();

        foreach ($originalConditions as $c) {
            $cl = clone $c;
            $cl->setCapture($this);

            if ($src = $c->getSourceElement()) {
                $cl->setSourceElement($elementMap[$src->getId()] ?? null);
            }
            if ($tgt = $c->getTargetElement()) {
                $cl->setTargetElement($elementMap[$tgt->getId()] ?? null);
            }
            if ($sf = $c->getSourceField()) {
                $origElId = $sf->getCaptureElement()->getId();
                $cl->setSourceField($fieldMapByElement[$origElId][$sf->getId()] ?? null);
            }

            $this->conditions->add($cl);
        }

        // title
        $clonedTitle = clone $this->title;
        $this->setTitle($clonedTitle);
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
     * @return Collection<int, CaptureElement>
     */
    public function getCaptureElements(): Collection
    {
        return $this->captureElements;
    }

    public function addCaptureElement(CaptureElement $captureElement): static
    {
        if (!$this->captureElements->contains($captureElement)) {
            $this->captureElements->add($captureElement);
        }

        return $this;
    }

    public function removeCaptureElement(CaptureElement $captureElement): static
    {
        $this->captureElements->removeElement($captureElement);

        return $this;
    }

    /** @return ParticipantRole[] **/
    public function getRespondentRoles(): array
    {
        $u = [];
        foreach ($this->getCaptureElements() as $el) {
            /** @var CaptureElement $el */
            if ($r = $el->getRespondent()) {
                $u[$r->getId()] = $r;
            }
        }
        return array_values($u);
    }

    /** @return ParticipantRole[] **/
    public function getResponsibleRoles(): array
    {
        $u = [];
        foreach ($this->getCaptureElements() as $el) {
            if ($r = $el->getResponsible()) {
                $u[$r->getId()] = $r;
            }
        }
        return array_values($u);
    }

    /** @return ParticipantRole[] **/
    public function getValidatorRoles(): array
    {
        $u = [];
        foreach ($this->getCaptureElements() as $el) {
            if ($r = $el->getValidator()) {
                $u[$r->getId()] = $r;
            }
        }
        return array_values($u);
    }

    /**
     * For displaying roles by elements"
     * @return array<int, array{
     *   element: CaptureElement,
     *   respondent: ?ParticipantRole,
     *   responsible: ?ParticipantRole,
     *   validator: ?ParticipantRole
     * }>
     */
    public function getCaptureElementsWithTypedRoles(): array
    {
        $rows = [];
        foreach ($this->getCaptureElements() as $element) {
            $rows[] = [
                'element'    => $element,
                'respondent' => $element->getRespondentRole(),
                'responsible'=> $element->getResponsibleRole(),
                'validator'  => $element->getValidatorRole(),
            ];
        }
        return $rows;
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

    public function getTitle(): ?Title
    {
        return $this->title;
    }

    public function setTitle(Title $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Condition>
     */
    public function getConditions(): Collection
    {
        return $this->conditions;
    }

    public function addCondition(Condition $condition): static
    {
        if (!$this->conditions->contains($condition)) {
            $this->conditions->add($condition);
            $condition->setCapture($this);
        }

        return $this;
    }

    public function removeCondition(Condition $condition): static
    {
        if ($this->conditions->removeElement($condition)) {
            if ($condition->getCapture() === $this) {
                $condition->setCapture(null);
            }
        }

        return $this;
    }
}
