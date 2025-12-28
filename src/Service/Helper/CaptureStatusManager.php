<?php

namespace App\Service\Helper;

use App\Entity\Capture\CaptureElement\CaptureElement;
use App\Entity\Participant\User;
use App\Enum\CaptureElementStatus;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CaptureStatusManager
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function transition(CaptureElement $element, CaptureElementStatus $to, bool $flush = true): void
    {
        $from = $element->getStatus();

        if ($from === $to) {
            return;
        }

        if (!$this->isAllowedTransition($from, $to)) {
            throw new \LogicException(sprintf('Invalid status transition "%s" -> "%s" for CaptureElement #%s.', $from->value, $to->value, (string) ($element->getId() ?? 'new')));
        }

        $element->setStatus($to);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function launch(CaptureElement $element, bool $flush = true): void
    {
        // READY -> COLLECTING
        $this->transition($element, CaptureElementStatus::COLLECTING, $flush);
    }

    public function markPending(CaptureElement $element, bool $flush = true): void
    {
        // COLLECTING/READY -> PENDING
        $this->transition($element, CaptureElementStatus::PENDING, $flush);
    }

    public function validate(CaptureElement $element, bool $flush = true): void
    {
        // SUBMITTED -> VALIDATED
        $this->transition($element, CaptureElementStatus::VALIDATED, $flush);
    }

    private function isAllowedTransition(CaptureElementStatus $from, CaptureElementStatus $to): bool
    {
        // TODO: Make allowed transition map
        /*$map = [
            CaptureElementStatus::TEMPLATE => [CaptureElementStatus::DRAFT],
            CaptureElementStatus::DRAFT => [CaptureElementStatus::READY],
            CaptureElementStatus::READY => [CaptureElementStatus::COLLECTING, CaptureElementStatus::PENDING],
            CaptureElementStatus::COLLECTING => [CaptureElementStatus::PENDING, CaptureElementStatus::SUBMITTED],
            CaptureElementStatus::PENDING => [CaptureElementStatus::COLLECTING, CaptureElementStatus::SUBMITTED],
            CaptureElementStatus::SUBMITTED => [CaptureElementStatus::VALIDATED],
            CaptureElementStatus::VALIDATED => [],
        ];

        return in_array($to, $map[$from] ?? [], true);*/
        return true;
    }

    private function userHasContributorRole(CaptureElement $element, User $user): bool
    {
        $contributorRole = $element->getContributor();
        if (null === $contributorRole) {
            return true;
        }

        foreach ($user->getParticipantRoles() as $role) {
            if (null !== $role->getId() && null !== $contributorRole->getId()) {
                if ($role->getId() === $contributorRole->getId()) {
                    return true;
                }
                continue;
            }

            // Fallback for non-persisted entities (no id yet)
            if ($role === $contributorRole) {
                return true;
            }
        }

        return false;
    }

    private function assignmentHasContributorRole(CaptureElement $element, User $user): bool
    {
        // ToDo: check if participantAssignments contributor role needed
        /*$contributorRole = $element->getContributor();
        if (null === $contributorRole) {
            return true;
        }

        foreach ($user->getParticipantRoles() as $role) {
            if (null !== $role->getId() && null !== $contributorRole->getId()) {
                if ($role->getId() === $contributorRole->getId()) {
                    return true;
                }
                continue;
            }

            // Fallback for non-persisted entities (no id yet)
            if ($role === $contributorRole) {
                return true;
            }
        }*/

        return false;
    }

    public function refresh(CaptureElement $element, User $user, bool $flush): void
    {
        // DRAFT/PENDING -> READY/PENDING
        if (CaptureElementStatus::DRAFT === $element->getStatus()) {
            // TODO: add or contributor participantAssignment HasContributorRole
            if ($this->userHasContributorRole($element, $user)) {
                $this->transition($element, CaptureElementStatus::READY, $flush);
            } elseif ($this->assignmentHasContributorRole($element, $user)) {
                $this->transition($element, CaptureElementStatus::PENDING, $flush);
                $element->setActivationMessage('Contributor missing');
            }
        }
    }

    public function submit(CaptureElement $element, User $user, bool $flush): void
    {
        // COLLECTING/READY -> SUBMITTED
        $this->transition($element, CaptureElementStatus::SUBMITTED, $flush);
    }

    public function valid(CaptureElement $element, User $user, bool $flush): void
    {
        // SUBMITTED -> VALIDATED
        $this->transition($element, CaptureElementStatus::VALIDATED, $flush);
    }
}
