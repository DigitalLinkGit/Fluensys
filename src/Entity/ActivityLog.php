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

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ORM\ManyToOne(targetEntity: Project::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Project $project = null;

    #[ORM\ManyToOne(targetEntity: Capture::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Capture $capture = null;

    #[ORM\ManyToOne(targetEntity: CaptureElement::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?CaptureElement $captureElement = null;

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

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

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

    public function getCaptureElement(): ?CaptureElement
    {
        return $this->captureElement;
    }

    public function setCaptureElement(?CaptureElement $captureElement): self
    {
        $this->captureElement = $captureElement;

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
}
