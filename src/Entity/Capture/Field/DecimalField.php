<?php

namespace App\Entity\Capture\Field;

use App\Repository\DecimalFieldRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DecimalFieldRepository::class)]
final class DecimalField extends Field
{
    #[ORM\Column(type: 'decimal', precision: 14, scale: 4, nullable: true)]
    private ?string $value = null;

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;

        return $this;
    }
}
