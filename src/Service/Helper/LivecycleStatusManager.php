<?php

namespace App\Service\Helper;

use App\Entity\Capture\Capture;
use App\Entity\Capture\CaptureElement;
use App\Entity\Enum\LivecycleStatus;
use App\Entity\Interface\LivecycleStatusAwareInterface;
use App\Entity\Project;
use App\Entity\Tenant\User;
use Doctrine\ORM\EntityManagerInterface;

final readonly class LivecycleStatusManager
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function publishTemplate(LivecycleStatusAwareInterface $entity, User $user, bool $flush = true): void
    {
        $this->transition($entity, LivecycleStatus::TEMPLATE, $user, $flush);
        if ($entity instanceof Capture) {
            foreach ($entity->getCaptureElements() as $element) {
                $this->publishTemplate($element, $user, $flush);
            }
        }
        if ($entity instanceof Project) {
            foreach ($entity->getCaptures() as $capture) {
                $this->publishTemplate($capture, $user, $flush);
            }
        }
        if ($flush) {
            $this->em->flush();
        }
    }

    public function unpublishTemplate(LivecycleStatusAwareInterface $entity, User $user, bool $flush = true): void
    {
        // ToDo : check if capture template is used in project template
        $this->transition($entity, LivecycleStatus::DRAFT, $user, $flush);
        if ($entity instanceof Capture) {
            foreach ($entity->getCaptureElements() as $element) {
                $this->unpublishTemplate($element, $user, $flush);
            }
        }
        if ($flush) {
            $this->em->flush();
        }
    }

    public function init(LivecycleStatusAwareInterface $entity, User $user, bool $flush = true): void
    {
        if ($entity->isTemplate()) {
            $this->transition($entity, LivecycleStatus::IN_PREPARATION, $user, $flush);
        }

        if ($entity instanceof Capture) {
            foreach ($entity->getCaptureElements() as $element) {
                $this->init($element, $user, $flush);
            }
        }
        if ($entity instanceof Project) {
            foreach ($entity->getCaptures() as $capture) {
                $this->init($capture, $user, $flush);
            }
        }
        if ($flush) {
            $this->em->flush();
        }
    }

    public function start(LivecycleStatusAwareInterface $entity, User $user, bool $flush = true): void
    {
        $this->transition($entity, LivecycleStatus::READY, $user, $flush);

        if ($entity instanceof Capture) {
            foreach ($entity->getCaptureElements() as $element) {
                $this->start($element, $user, $flush);
            }
        }
        if ($entity instanceof Project) {
            foreach ($entity->getCaptures() as $capture) {
                $this->start($capture, $user, $flush);
            }
        }
        if ($flush) {
            $this->em->flush();
        }
    }

    public function startCollecting(LivecycleStatusAwareInterface $entity, User $user, bool $flush = true): void
    {
        // READY -> COLLECTING
        $this->transition($entity, LivecycleStatus::COLLECTING, $user, $flush);
    }

    public function markPending(LivecycleStatusAwareInterface $entity, User $user, ?string $reason = null, bool $flush = true): void
    {
        // READY/COLLECTING -> PENDING
        $this->transition($entity, LivecycleStatus::PENDING, $user, $flush);
    }

    public function submit(LivecycleStatusAwareInterface $entity, User $user, bool $flush = true): void
    {
        if ($entity instanceof CaptureElement) {
            $this->transition($entity, LivecycleStatus::SUBMITTED, $user, $flush);
        }
    }

    public function validate(LivecycleStatusAwareInterface $entity, User $user, bool $flush = true): void
    {
        // SUBMITTED -> VALIDATED
        $this->transition($entity, LivecycleStatus::VALIDATED, $user, $flush);
    }

    public function unvalidate(LivecycleStatusAwareInterface $entity, User $user, bool $flush = true): void
    {
        // VALIDATED -> SUBMITTED
        $this->transition($entity, LivecycleStatus::SUBMITTED, $user, $flush);
    }

    public function refresh(LivecycleStatusAwareInterface $entity, User $user, bool $flush): void
    {
        // Do not refresh "In preparation" status, wait start action form user
        if(!$entity->isinPreparation()) {
            // ToDo : add condition toggler here
            if ($entity instanceof CaptureElement) {
                if ($entity->isValidated() || $entity->isCollecting()) {
                    return;
                }
                if ($entity->issubmitted()) {
                    if (!$this->userHasValidatorRole($entity, $user) && !$this->assignmentHasValidatorRole($entity)) {
                        $this->transition($entity, LivecycleStatus::PENDING, $user, $flush);
                    }
                } else {
                    if ($this->userHasContributorRole($entity, $user) || $this->assignmentHasContributorRole($entity)) {
                        $this->transition($entity, LivecycleStatus::READY, $user, $flush);
                    } else {
                        $this->transition($entity, LivecycleStatus::PENDING, $user, $flush);
                    }
                }

                return;
            }

            if ($entity instanceof Capture) {
                foreach ($entity->getCaptureElements() as $element) {
                    $this->refresh($element, $user, false);
                }

                $this->refreshParentStatusFromChilds($entity, $user);


                if ($flush) {
                    $this->em->flush();
                }

                return;
            }

            if ($entity instanceof Project) {
                foreach ($entity->getCaptures() as $capture) {
                    $this->refresh($capture, $user, false);
                }
                foreach ($entity->getRecurringCaptures() as $capture) {
                    $this->refresh($capture, $user, false);
                }
                $this->refreshParentStatusFromChilds($entity, $user);

                if ($flush) {
                    $this->em->flush();
                }

                return;
            }

            throw new \LogicException(sprintf('Unsupported entity for refresh(): %s', $entity::class));
        }
    }

    private function refreshParentStatusFromChilds(LivecycleStatusAwareInterface $parent, User $user): void
    {
        // safety: never downgrade a validated capture
        if ($parent->isValidated()) {
            return;
        }

        $childs = null;

        if ($parent instanceof Capture) {
            $childs = $parent->getCaptureElements();
        }

        if ($parent instanceof Project) {
            $childs = $parent->getCaptures();
        }

        if (null === $childs) {
            return;
        }

        if (0 === \count($childs)) {
            return;
        }

        $statuses = [];
        foreach ($childs as $child) {
            // ToDo : take only active child
            $statuses[] = $child->getStatus();
        }

        // VALIDATED
        $allValidated = \count($statuses) === \count(array_filter(
            $statuses,
            static fn (LivecycleStatus $s) => LivecycleStatus::VALIDATED === $s
        ));

        if ($allValidated) {
            $this->transition($parent, LivecycleStatus::VALIDATED, $user, false);

            return;
        }

        // SUBMITTED
        $allSubmitted = \count($statuses) === \count(array_filter(
            $statuses,
            static fn (LivecycleStatus $s) => LivecycleStatus::SUBMITTED === $s
        ));

        if ($allSubmitted) {
            $this->transition($parent, LivecycleStatus::SUBMITTED, $user, false);

            return;
        }
        // READY
        $allReady = \count($statuses) === \count(array_filter(
            $statuses,
            static fn (LivecycleStatus $s) => LivecycleStatus::READY === $s
        ));

        if ($allReady) {
            $this->transition($parent, LivecycleStatus::READY, $user, false);

            return;
        }

        // COLLECTING
        if (\in_array(LivecycleStatus::COLLECTING, $statuses, true)) {
            $this->transition($parent, LivecycleStatus::COLLECTING, $user, false);

            return;
        }

        // PENDING
        if (\in_array(LivecycleStatus::PENDING, $statuses, true)) {
            $this->transition($parent, LivecycleStatus::PENDING, $user, false);

            return;
        }
    }

    private function transition(LivecycleStatusAwareInterface $entity, LivecycleStatus $to, User $user, bool $flush = true): void
    {
        $from = $entity->getStatus();

        if ($from === $to) {
            return;
        }

        if (!$this->isAllowedTransition($from, $to)) {
            throw new \LogicException(sprintf('Invalid status transition "%s" -> "%s" for %s #%s.', $from->value, $to->value, $entity::class, (string) ($entity->getId() ?? 'new')));
        }

        $entity->setStatus($to);

        if ($flush) {
            $this->em->flush();
        }
    }

    private function isAllowedTransition(LivecycleStatus $from, LivecycleStatus $to): bool
    {
        $map = [
            LivecycleStatus::DRAFT->value => [LivecycleStatus::TEMPLATE],
            LivecycleStatus::TEMPLATE->value => [LivecycleStatus::IN_PREPARATION],
            LivecycleStatus::IN_PREPARATION->value => [LivecycleStatus::READY, LivecycleStatus::PENDING, LivecycleStatus::COLLECTING],
            LivecycleStatus::READY->value => [LivecycleStatus::COLLECTING, LivecycleStatus::PENDING, LivecycleStatus::SUBMITTED],
            LivecycleStatus::COLLECTING->value => [LivecycleStatus::SUBMITTED],
            LivecycleStatus::PENDING->value => [LivecycleStatus::READY],
            LivecycleStatus::SUBMITTED->value => [LivecycleStatus::VALIDATED, LivecycleStatus::PENDING],
            LivecycleStatus::VALIDATED->value => [],
        ];

        return \in_array($to, $map[$from->value] ?? [], true);
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

    private function userHasValidatorRole(CaptureElement $element, User $user): bool
    {
        $validator = $element->getValidator();
        if (null === $validator) {
            return true;
        }

        foreach ($user->getParticipantRoles() as $role) {
            if (null !== $role->getId() && null !== $validator->getId()) {
                if ($role->getId() === $validator->getId()) {
                    return true;
                }
                continue;
            }

            // Fallback for non-persisted entities (no id yet)
            if ($role === $validator) {
                return true;
            }
        }

        return false;
    }

    private function assignmentHasContributorRole(CaptureElement $element): bool
    {
        $contributorRole = $element->getContributor();
        if (null === $contributorRole) {
            return true;
        }

        foreach ($element->getCapture()->getParticipantAssignments() as $assignment) {
            if ($assignment->getRole()->getId() == $contributorRole->getId()) {
                return true;
            }
        }

        return false;
    }

    private function assignmentHasValidatorRole(CaptureElement $element): bool
    {
        $validator = $element->getValidator();
        if (null === $validator) {
            return true;
        }

        foreach ($element->getCapture()->getParticipantAssignments() as $assignment) {
            if ($assignment->getRole()->getId() == $validator->getId()) {
                return true;
            }
        }

        return false;
    }
}
