<?php

namespace App\Entity\Field;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IntegerFieldRepository::class)]
class IntegerField extends Field
{
    #[ORM\Column(length: 255,nullable:  true)]
    private ?int $value = null;

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
