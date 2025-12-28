<?php

declare(strict_types=1);

namespace App\Doctrine\Filter;

use App\Entity\TenantAwareInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

final class TenantFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
    {
        // Apply only to entities that are tenant-scoped
        if (!$targetEntity->reflClass->implementsInterface(TenantAwareInterface::class)) {
            return '';
        }

        // Assume join column is named "tenant_id"
        // setParameter('tenant_id', '<id>') must be done when enabling the filter.
        return sprintf('%s.tenant_id = %s', $targetTableAlias, $this->getParameter('tenant_id'));
    }
}
