<?php

namespace App\Entity\Participant;

use App\Entity\CaptureElement;
use App\Repository\ParticipantRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipantRoleRepository::class)]
class ParticipantRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $internal = null;

    /**
     * @var Collection<int, CaptureElement>
     */
    #[ORM\ManyToMany(targetEntity: CaptureElement::class, mappedBy: 'participantRoles')]
    private Collection $captureElements;

    public function __construct()
    {
        $this->captureElements = new ArrayCollection();
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

    public function isInternal(): ?bool
    {
        return $this->internal;
    }

    public function setInternal(bool $internal): static
    {
        $this->internal = $internal;

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
            $captureElement->addParticipantRole($this);
        }

        return $this;
    }

    public function removeCaptureElement(CaptureElement $captureElement): static
    {
        if ($this->captureElements->removeElement($captureElement)) {
            $captureElement->removeParticipantRole($this);
        }

        return $this;
    }
}
