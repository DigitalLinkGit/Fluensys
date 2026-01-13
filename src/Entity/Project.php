<?php

namespace App\Entity;

use App\Entity\Account\Account;
use App\Entity\Capture\Capture;
use App\Entity\Interface\LivecycleStatusAwareInterface;
use App\Entity\Interface\TenantAwareInterface;
use App\Entity\Participant\ParticipantAssignment;
use App\Entity\Tenant\User;
use App\Entity\Trait\LivecycleStatusTrait;
use App\Entity\Trait\TenantAwareTrait;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project implements TenantAwareInterface, LivecycleStatusAwareInterface
{
    use TenantAwareTrait;
    use LivecycleStatusTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, Capture>
     */
    #[ORM\ManyToMany(targetEntity: Capture::class, inversedBy: 'projects', cascade: ['persist'])]
    #[ORM\JoinTable(
        name: 'project_capture',
        joinColumns: [
            new ORM\JoinColumn(name: 'project_id', referencedColumnName: 'id', onDelete: 'CASCADE'),
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'capture_id', referencedColumnName: 'id', onDelete: 'CASCADE'),
        ]
    )]
    private Collection $captures;

    /**
     * @var Collection<int, Capture>
     */
    #[ORM\ManyToMany(targetEntity: Capture::class, inversedBy: 'recurringCaptureProjects', cascade: ['persist'])]
    #[ORM\JoinTable(
        name: 'project_recurring_capture',
        joinColumns: [
            new ORM\JoinColumn(name: 'project_id', referencedColumnName: 'id', onDelete: 'CASCADE'),
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'capture_id', referencedColumnName: 'id', onDelete: 'CASCADE'),
        ]
    )]
    private Collection $recurringCaptures;

    /**
     * @var Collection<int, Capture>
     */
    #[ORM\ManyToMany(targetEntity: Capture::class, inversedBy: 'recurringCaptureTemplateProjects', cascade: ['persist'])]
    #[ORM\JoinTable(
        name: 'project_recurring_capture_templates',
        joinColumns: [
            new ORM\JoinColumn(name: 'project_id', referencedColumnName: 'id', onDelete: 'CASCADE'),
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'capture_id', referencedColumnName: 'id', onDelete: 'CASCADE'),
        ]
    )]
    private Collection $recurringCapturesTemplates;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?Account $account = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?User $responsible = null;

    #[ORM\OneToMany(targetEntity: ParticipantAssignment::class, mappedBy: 'project', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $participantAssignments;

    public function __construct()
    {
        $this->captures = new ArrayCollection();
        $this->recurringCaptures = new ArrayCollection();
        $this->participantAssignments = new ArrayCollection();
    }

    public function __clone()
    {
        $this->id = null;

        $originalCaptures = $this->captures;
        $this->captures = new ArrayCollection();

        foreach ($originalCaptures as $el) {
            $cl = clone $el;
            $this->addCapture($cl);
        }
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
     * @return Collection<int, Capture>
     */
    public function getCaptures(): Collection
    {
        return $this->captures;
    }

    public function addCapture(Capture $capture): static
    {
        if (!$this->captures->contains($capture)) {
            $this->captures->add($capture);
        }

        return $this;
    }

    public function removeCapture(Capture $capture): static
    {
        $this->captures->removeElement($capture);
        $capture->removeProject($this);

        return $this;
    }

    /**
     * @return Collection<int, Capture>
     */
    public function getRecurringCaptures(): Collection
    {
        return $this->recurringCaptures;
    }

    public function addRecurringCapture(Capture $recurringCapture): static
    {
        if (!$this->recurringCaptures->contains($recurringCapture)) {
            $this->recurringCaptures->add($recurringCapture);
        }

        return $this;
    }

    public function removeRecurringCapture(Capture $recurringCapture): static
    {
        $this->recurringCaptures->removeElement($recurringCapture);
        $recurringCapture->removeProject($this);

        return $this;
    }

    public function getRecurringCapturesTemplates(): Collection
    {
        return $this->recurringCapturesTemplates;
    }

    public function addRecurringCapturesTemplates(Capture $recurringCaptureTemplate): static
    {
        if (!$this->recurringCapturesTemplates->contains($recurringCaptureTemplate)) {
            $this->recurringCapturesTemplates->add($recurringCaptureTemplate);
        }

        return $this;
    }

    public function removeRecurringCapturesTemplates(Capture $recurringCaptureTemplate): static
    {
        $this->recurringCapturesTemplates->removeElement($recurringCaptureTemplate);
        $recurringCaptureTemplate->removeProject($this);

        return $this;
    }

    public function getContributorRoles(): array
    {
        $u = [];

        foreach ($this->getCaptures() as $capture) {
            foreach ($capture->getContributorRoles() as $r) {
                $u[$r->getId()] = $r;
            }
        }

        foreach ($this->getRecurringCapturesTemplates() as $capture) {
            foreach ($capture->getContributorRoles() as $r) {
                $u[$r->getId()] = $r;
            }
        }

        return array_values($u);
    }
    public function getValidatorRoles(): array
    {
        $u = [];

        foreach ($this->getCaptures() as $capture) {
            foreach ($capture->getValidatorRoles() as $r) {
                $u[$r->getId()] = $r;
            }
        }

        foreach ($this->getRecurringCapturesTemplates() as $capture) {
            foreach ($capture->getValidatorRoles() as $r) {
                $u[$r->getId()] = $r;
            }
        }

        return array_values($u);
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getResponsible(): ?User
    {
        return $this->responsible;
    }

    public function setResponsible(?User $responsible): static
    {
        $this->responsible = $responsible;

        return $this;
    }

    public function getParticipantAssignments(): Collection
    {
        return $this->participantAssignments;
    }

    public function addParticipantAssignment(ParticipantAssignment $participantAssignment): static
    {
        if (!$this->participantAssignments->contains($participantAssignment)) {
            $this->participantAssignments->add($participantAssignment);
            $participantAssignment->setProject($this);
        }

        return $this;
    }

    public function removeParticipantAssignment(ParticipantAssignment $participantAssignment): static
    {
        if ($this->participantAssignments->removeElement($participantAssignment)) {
            if ($participantAssignment->getCapture() === $this) {
                $participantAssignment->setCapture(null);
            }
        }

        return $this;
    }

}
