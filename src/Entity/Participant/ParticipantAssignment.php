<?php

namespace App\Entity\Participant;

use App\Entity\Account\Contact;
use App\Entity\Capture\Capture;
use App\Entity\Tenant\User;
use App\Repository\ParticipantAssignmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: ParticipantAssignmentRepository::class)]
#[ORM\Table(name: 'participant_assignment')]
#[ORM\UniqueConstraint(name: 'uniq_capture_role', columns: ['capture_id', 'role_id'])]
#[UniqueEntity(fields: ['capture', 'role'], message: 'This role is already assigned for this capture.')]
class ParticipantAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Capture::class, inversedBy: 'participantAssignments')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Capture $capture = null;

    #[ORM\ManyToOne(targetEntity: ParticipantRole::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?ParticipantRole $role = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Contact::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Contact $contact = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCapture(): ?Capture
    {
        return $this->capture;
    }

    public function setCapture(?Capture $capture): static
    {
        $this->capture = $capture;

        return $this;
    }

    public function getRole(): ?ParticipantRole
    {
        return $this->role;
    }

    public function setRole(?ParticipantRole $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        $hasUser = null !== $this->user;
        $hasContact = null !== $this->contact;

        if ($hasUser === $hasContact) {
            $context->buildViolation('Exactly one of "user" or "contact" must be set.')
                ->atPath('user')
                ->addViolation();

            return;
        }

        if (null === $this->role) {
            return;
        }

        $isInternal = (bool) $this->role->isInternal();

        if ($isInternal && !$hasUser) {
            $context->buildViolation('Internal roles must be assigned to a user.')
                ->atPath('user')
                ->addViolation();
        }

        if (!$isInternal && !$hasContact) {
            $context->buildViolation('External roles must be assigned to a contact.')
                ->atPath('contact')
                ->addViolation();
        }

        if ($isInternal && $hasContact) {
            $context->buildViolation('Internal roles cannot be assigned to a contact.')
                ->atPath('contact')
                ->addViolation();
        }

        if (!$isInternal && $hasUser) {
            $context->buildViolation('External roles cannot be assigned to a user.')
                ->atPath('user')
                ->addViolation();
        }
    }
}
