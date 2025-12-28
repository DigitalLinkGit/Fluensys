<?php

namespace App\Entity\Capture\Field;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class ChecklistField extends Field
{
    #[ORM\Column(type: 'json')]
    private array $choices = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $value = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $uniqueResponse = false;

    public function isUniqueResponse(): bool
    {
        return $this->uniqueResponse;
    }

    public function setUniqueResponse(bool $uniqueResponse): static
    {
        $this->uniqueResponse = $uniqueResponse;

        return $this;
    }

    public function getValue(): ?array
    {
        return $this->value;
    }

    public function setValue(array|string|null $value): static
    {
        if ('' === $value || null === $value) {
            $this->value = null;

            return $this;
        }

        // Radio => string
        if (is_string($value)) {
            $this->value = [$value];

            return $this;
        }

        // Checkbox => array
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
            if ('' === $label) {
                continue;
            }
            $out[$label] = $value;
        }

        return $out;
    }


}
