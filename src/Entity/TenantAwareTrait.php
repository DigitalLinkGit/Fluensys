<?php

namespace App\Entity;

use App\Entity\Tenant;
use Doctrine\ORM\Mapping as ORM;

trait TenantAwareTrait
{
    #[ORM\ManyToOne(targetEntity: Tenant::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private ?Tenant $tenant = null;

    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function setTenant(Tenant $tenant): self
    {
        $this->tenant = $tenant;

        return $this;
    }
}
