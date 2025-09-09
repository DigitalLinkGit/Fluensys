<?php

namespace App\Entity;

use App\Repository\CaptureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaptureRepository::class)]
class Capture
{
    public function __clone()
    {
        $this->id = null;

        $originals = $this->captureElements instanceof \Doctrine\Common\Collections\Collection
            ? $this->captureElements->toArray() // snapshot
            : (array) $this->captureElements;

        $newElements = new ArrayCollection();
        foreach ($originals as $el) {
            $cloned = clone $el;
            $cloned->setTemplate(false);
            $newElements->add($cloned);
        }
        $this->captureElements = $newElements;
    }
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

    #[ORM\OneToOne(targetEntity: Title::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Title $title = null;

    public function __construct()
    {
        $this->captureElements = new ArrayCollection();
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
}
