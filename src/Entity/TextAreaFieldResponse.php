<?php

namespace App\Entity;

use App\Repository\TextAreaFieldResponseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TextAreaFieldResponseRepository::class)]
class TextAreaFieldResponse extends FieldResponse
{
    #[ORM\Column(length: 255)]
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
