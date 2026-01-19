<?php

namespace App\Entity\Interface;

use App\Entity\Enum\LivecycleStatus;

interface LivecycleStatusAwareInterface
{
    public function getStatus(): LivecycleStatus;

    public function setStatus(LivecycleStatus $status): static;

    public function getId(): ?int;
}
