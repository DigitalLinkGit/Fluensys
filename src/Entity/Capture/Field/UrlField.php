<?php

namespace App\Entity\Capture\Field;

use App\Repository\UrlFieldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UrlFieldRepository::class)]
class UrlField extends \App\Entity\Capture\Field\Field
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
