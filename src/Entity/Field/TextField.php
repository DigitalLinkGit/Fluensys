<?php

namespace App\Entity\Field;

use App\Repository\TextAreaFieldRepository;
use App\Repository\TextFieldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TextFieldRepository::class)]
class TextField extends Field
{
    #[ORM\Column(length: 255,nullable:  true)]
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
