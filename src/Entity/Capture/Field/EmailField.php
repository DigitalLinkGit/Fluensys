<?php

namespace App\Entity\Capture\Field;

use App\Repository\EmailFieldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmailFieldRepository::class)]
final class EmailField extends Field
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $value = null;

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }
}
