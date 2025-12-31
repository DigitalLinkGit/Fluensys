<?php

namespace App\Entity\Tenant;

interface TenantAwareInterface
{
    public function getTenant(): ?Tenant;
    public function setTenant(Tenant $tenant): self;
}
