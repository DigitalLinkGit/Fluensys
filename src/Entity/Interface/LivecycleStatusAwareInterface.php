<?php

namespace App\Entity\Interface;

use App\Enum\LivecycleStatus;

interface LivecycleStatusAwareInterface
{
    public function getStatus(): LivecycleStatus;

    public function setStatus(LivecycleStatus $status): static;

    public function getId(): ?int;
}
