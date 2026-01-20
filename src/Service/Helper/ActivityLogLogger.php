<?php

namespace App\Service\Helper;

use App\Entity\Account\Contact;
use App\Entity\ActivityLog;
use App\Entity\Capture\Capture;
use App\Entity\Capture\CaptureElement;
use App\Entity\Enum\ActivityAction;
use App\Entity\Enum\ActivityActorType;
use App\Entity\Enum\ActivitySubjectType;
use App\Entity\Project;
use App\Entity\Tenant\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class ActivityLogLogger
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
    ) {
    }

    public function logForProject(
        Project $project,
        ActivityAction $action,
        ?User $actorUser = null,
        ?Contact $actorContact = null,
        ?\DateTimeImmutable $occurredAt = null,
        ?string $subjectLabel = null,
    ): ActivityLog {
        $log = $this->baseLog($action, $actorUser, $actorContact, $occurredAt);

        $log->setProject($project);
        $log->setCapture(null);
        $log->setCaptureElement(null);

        $log->setSubjectType(ActivitySubjectType::PROJECT);
        $log->setSubjectLabel($subjectLabel ?? $this->safeLabel($project, 'getName'));

        $this->em->persist($log);
        $this->em->flush();

        return $log;
    }

    public function logForCapture(
        Capture $capture,
        ActivityAction $action,
        ?User $actorUser = null,
        ?Contact $actorContact = null,
        ?\DateTimeImmutable $occurredAt = null,
        ?string $subjectLabel = null,
    ): ActivityLog {
        $log = $this->baseLog($action, $actorUser, $actorContact, $occurredAt);

        $log->setProject($capture->getOwnerProject());
        $log->setCapture($capture);
        $log->setCaptureElement(null);

        $log->setSubjectType(ActivitySubjectType::CAPTURE);
        $log->setSubjectLabel($subjectLabel ?? $this->safeLabel($capture, 'getName'));

        $this->em->persist($log);
        $this->em->flush();

        return $log;
    }

    public function logForCaptureElement(
        CaptureElement $captureElement,
        ActivityAction $action,
        ?User $actorUser = null,
        ?Contact $actorContact = null,
        ?\DateTimeImmutable $occurredAt = null,
        ?string $subjectLabel = null,
    ): ActivityLog {
        $log = $this->baseLog($action, $actorUser, $actorContact, $occurredAt);

        $capture = method_exists($captureElement, 'getCapture') ? $captureElement->getCapture() : null;
        $project = ($capture && method_exists($capture, 'getProject')) ? $capture->getProject() : null;

        $log->setProject($captureElement->getCapture()->getOwnerProject());
        $log->setCapture($captureElement->getCapture());
        $log->setCaptureElement($captureElement);

        $log->setSubjectType(ActivitySubjectType::CAPTURE_ELEMENT);
        $log->setSubjectLabel($subjectLabel ?? $this->safeLabel($captureElement, 'getName'));

        $this->em->persist($log);
        $this->em->flush();

        return $log;
    }

    private function baseLog(
        ActivityAction $action,
        ?User $actorUser,
        ?Contact $actorContact,
        ?\DateTimeImmutable $occurredAt,
    ): ActivityLog {
        if (null !== $actorUser && null !== $actorContact) {
            throw new \InvalidArgumentException('Provide either actorUser or actorContact, not both.');
        }

        $log = new ActivityLog();
        $log->setAction($action);

        if (null !== $occurredAt) {
            $log->setOccurredAt($occurredAt);
        }

        // Default actor to current authenticated user if no actor provided.
        if (null === $actorUser && null === $actorContact) {
            $user = $this->security->getUser();
            if ($user instanceof User) {
                $actorUser = $user;
            }
        }

        if (null !== $actorUser) {
            $log->setActorType(ActivityActorType::USER);
            $log->setActorUser($actorUser);
            $log->setActorContact(null);
        } elseif (null !== $actorContact) {
            $log->setActorType(ActivityActorType::CONTACT);
            $log->setActorContact($actorContact);
            $log->setActorUser(null);
        } else {
            $log->setActorType(ActivityActorType::SYSTEM);
            $log->setActorUser(null);
            $log->setActorContact(null);
        }

        return $log;
    }

    private function safeLabel(object $entity, string $getter): string
    {
        if (method_exists($entity, $getter)) {
            $value = $entity->{$getter}();
            if (is_string($value) && '' !== $value) {
                return $value;
            }
        }

        $short = (new \ReflectionClass($entity))->getShortName();

        return $short;
    }
}
