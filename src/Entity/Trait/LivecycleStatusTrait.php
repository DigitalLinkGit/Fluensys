<?php

namespace App\Entity\Trait;

use App\Entity\Enum\LivecycleStatus;
use Doctrine\ORM\Mapping as ORM;

trait LivecycleStatusTrait
{
    #[ORM\Column(enumType: LivecycleStatus::class, options: ['default' => LivecycleStatus::DRAFT])]
    private LivecycleStatus $status = LivecycleStatus::DRAFT;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    protected bool $active = true;

    public function getStatus(): LivecycleStatus
    {
        return $this->status;
    }

    public function setStatus(LivecycleStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusLabel(): string
    {
        return $this->status->getLabel();
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function isDraft(): bool
    {
        return LivecycleStatus::DRAFT === $this->status;
    }

    public function isTemplate(): bool
    {
        return LivecycleStatus::TEMPLATE === $this->status;
    }

    public function isPending(): bool
    {
        return LivecycleStatus::PENDING === $this->status;
    }

    public function isReady(): bool
    {
        return LivecycleStatus::READY === $this->status;
    }

    public function isSubmitted(): bool
    {
        return LivecycleStatus::SUBMITTED === $this->status;
    }

    public function isValidated(): bool
    {
        return LivecycleStatus::VALIDATED === $this->status;
    }

    public function isCollecting(): bool
    {
        return LivecycleStatus::COLLECTING === $this->status;
    }

}
