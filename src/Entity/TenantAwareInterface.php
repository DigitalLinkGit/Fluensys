<?php

namespace App\Entity;

interface TenantAwareInterface
{
    public function getTenant(): ?Tenant;
    public function setTenant(Tenant $tenant): self;
}
