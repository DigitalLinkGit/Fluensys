<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Participant\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class TenantFilterSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 0],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        dump('TENANT FILTER SUBSCRIBER HIT');
        if (!$event->isMainRequest()) {
            return;
        }

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return;
        }

        $tenant = $user->getTenant();
        if (null === $tenant) {
            return;
        }

        $filter = $this->em->getFilters()->enable('tenant');
        $filter->setParameter('tenant_id', (string) $tenant->getId());
    }
}
