<?php

namespace App\Entity\Capture\Field;

use App\Repository\DecimalFieldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DecimalFieldRepository::class)]
final class DecimalField extends Field
{
    public const TYPE = 'decimal';
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?float $value = null;

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): static
    {
        $this->value = $value;

        return $this;
    }
}
