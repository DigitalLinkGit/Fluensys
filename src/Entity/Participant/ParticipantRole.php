<?php

namespace App\Entity\Participant;

use App\Entity\Capture\CaptureElement\CaptureElement;
use App\Entity\User;
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

    #[ORM\OneToMany(targetEntity: CaptureElement::class, mappedBy: 'responsible')]
    private Collection $responsibleCaptureElements;

    #[ORM\OneToMany(targetEntity: CaptureElement::class, mappedBy: 'validator')]
    private Collection $validatorCaptureElements;

    #[ORM\OneToMany(targetEntity: CaptureElement::class, mappedBy: 'respondent')]
    private Collection $respondentCaptureElements;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'participantRoles')]
    private Collection $users;

    public function __construct()
    {
        $this->responsibleCaptureElements = new ArrayCollection();
        $this->validatorCaptureElements = new ArrayCollection();
        $this->respondentCaptureElements = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getResponsibleCaptureElements(): Collection
    {
        return $this->responsibleCaptureElements;
    }

    public function getValidatorCaptureElements(): Collection
    {
        return $this->validatorCaptureElements;
    }

    public function getRespondentCaptureElements(): Collection
    {
        return $this->respondentCaptureElements;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addParticipantRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeParticipantRole($this);
        }

        return $this;
    }
}
