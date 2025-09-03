<?php

namespace App\Entity\Field;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ChecklistField extends Field
{
    // Choices stored as array of ['label' => string, 'value' => string]
    #[ORM\Column(type: 'json')]
    private array $choices = [];

    // Multiple selected values; for checkboxes it's an array of strings
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $value = null;

    public function getValue(): ?array
    {
        return $this->value;
    }

    public function setValue(?array $value): static
    {
        $this->value = $value;
        return $this;
    }

    public function getChoices(): array
    {
        return $this->choices;
    }

    public function setChoices(array $choices): static
    {
        $this->choices = $choices;
        return $this;
    }

    public function toSymfonyChoices(): array
    {
        $out = [];
        foreach ($this->choices as $c) {
            $label = $c['label'] ?? '';
            $value = $c['value'] ?? $label;
            if ($label === '') { continue; }
            $out[$label] = $value;
        }
        return $out;
    }
}
