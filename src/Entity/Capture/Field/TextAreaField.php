<?php

namespace App\Entity\Capture\Field;

use App\Repository\TextAreaFieldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TextAreaFieldRepository::class)]
final class TextAreaField extends Field
{
    public const TYPE = 'textarea';
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
