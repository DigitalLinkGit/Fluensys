<?php

namespace App\Entity\Interface;

use App\Entity\Tenant\Tenant;

interface TenantAwareInterface
{
    public function getTenant(): ?Tenant;
    public function setTenant(Tenant $tenant): self;
}
