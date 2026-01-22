<?php

namespace App\Entity;

use App\Entity\Account\Account;
use App\Entity\Account\Contact;
use App\Entity\Capture\Capture;
use App\Entity\Capture\CaptureElement;
use App\Entity\Enum\ActivityAction;
use App\Entity\Enum\ActivityActorType;
use App\Entity\Enum\ActivitySubjectType;
use App\Entity\Tenant\User;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'activity_log')]
#[ORM\Index(name: 'idx_activity_occurred_at', columns: ['occurred_at'])]
#[ORM\Index(name: 'idx_activity_action', columns: ['action'])]
#[ORM\Index(name: 'idx_activity_project', columns: ['project_id'])]
#[ORM\Index(name: 'idx_activity_capture', columns: ['capture_id'])]
#[ORM\Index(name: 'idx_activity_capture_element', columns: ['capture_element_id'])]
final class ActivityLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'occurred_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $occurredAt;

    #[ORM\Column(type: 'string', enumType: ActivityAction::class)]
    private ActivityAction $action;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $accountId = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $accountName = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $projectId = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $projectName = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $captureId = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $captureName = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $captureElementId = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $captureElementName = null;


    #[ORM\Column(name: 'subject_type', type: 'string', enumType: ActivitySubjectType::class)]
    private ActivitySubjectType $subjectType;

    #[ORM\Column(name: 'subject_label', type: 'string', length: 255)]
    private string $subjectLabel;

    #[ORM\Column(name: 'actor_type', type: 'string', enumType: ActivityActorType::class)]
    private ActivityActorType $actorType;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $actorUser = null;

    #[ORM\ManyToOne(targetEntity: Contact::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Contact $actorContact = null;

    public function __construct()
    {
        $this->occurredAt = new \DateTimeImmutable();
        $this->actorType = ActivityActorType::SYSTEM;
        $this->subjectLabel = '';
        $this->subjectType = ActivitySubjectType::PROJECT;
        $this->action = ActivityAction::UPDATED;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOccurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }

    public function setOccurredAt(\DateTimeImmutable $occurredAt): self
    {
        $this->occurredAt = $occurredAt;

        return $this;
    }

    public function getAction(): ActivityAction
    {
        return $this->action;
    }

    public function setAction(ActivityAction $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getSubjectType(): ActivitySubjectType
    {
        return $this->subjectType;
    }

    public function setSubjectType(ActivitySubjectType $subjectType): self
    {
        $this->subjectType = $subjectType;

        return $this;
    }

    public function getSubjectLabel(): string
    {
        return $this->subjectLabel;
    }

    public function setSubjectLabel(string $subjectLabel): self
    {
        $this->subjectLabel = $subjectLabel;

        return $this;
    }

    public function getActorType(): ActivityActorType
    {
        return $this->actorType;
    }

    public function setActorType(ActivityActorType $actorType): self
    {
        $this->actorType = $actorType;

        return $this;
    }

    public function getActorUser(): ?User
    {
        return $this->actorUser;
    }

    public function setActorUser(?User $actorUser): self
    {
        $this->actorUser = $actorUser;

        return $this;
    }

    public function getActorContact(): ?Contact
    {
        return $this->actorContact;
    }

    public function setActorContact(?Contact $actorContact): self
    {
        $this->actorContact = $actorContact;

        return $this;
    }

    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    public function setAccountId(?int $accountId): static
    {
        $this->accountId = $accountId;

        return $this;
    }

    public function getAccountName(): ?string
    {
        return $this->accountName;
    }

    public function setAccountName(?string $accountName): static
    {
        $this->accountName = $accountName;

        return $this;
    }

    public function getProjectId(): ?int
    {
        return $this->projectId;
    }

    public function setProjectId(?int $projectId): static
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getProjectName(): ?string
    {
        return $this->projectName;
    }

    public function setProjectName(?string $projectName): static
    {
        $this->projectName = $projectName;

        return $this;
    }

    public function getCaptureId(): ?int
    {
        return $this->captureId;
    }

    public function setCaptureId(?int $captureId): static
    {
        $this->captureId = $captureId;

        return $this;
    }

    public function getCaptureName(): ?string
    {
        return $this->captureName;
    }

    public function setCaptureName(?string $captureName): static
    {
        $this->captureName = $captureName;

        return $this;
    }

    public function getCaptureElementId(): ?int
    {
        return $this->captureElementId;
    }

    public function setCaptureElementId(?int $captureElementId): static
    {
        $this->captureElementId = $captureElementId;

        return $this;
    }

    public function getCaptureElementName(): ?string
    {
        return $this->captureElementName;
    }

    public function setCaptureElementName(?string $captureElementName): static
    {
        $this->captureElementName = $captureElementName;

        return $this;
    }
}
