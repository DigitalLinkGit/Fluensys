<?php

declare(strict_types=1);

namespace App\Doctrine\EventSubscriber;

use App\Entity\Tenant\TenantAwareInterface;
use App\Entity\Tenant\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsDoctrineListener(event: Events::prePersist)]
final readonly class TenantAutofillSubscriber
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof TenantAwareInterface) {
            return;
        }

        if (null !== $entity->getTenant()) {
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

        $entity->setTenant($tenant);
    }
}
