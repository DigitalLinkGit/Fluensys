<?php

namespace App\Entity\Capture\Field;

use App\Repository\TextListFieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TextListFieldRepository::class)]
final class TextListField extends Field
{
    #[ORM\ManyToMany(targetEntity: ListableTextField::class, cascade: ['persist'])]
    private Collection $strings;

    public function __construct()
    {
        $this->strings = new ArrayCollection();
    }

    public function getStrings(): Collection
    {
        return $this->strings;
    }

    public function addString(ListableTextField $text): self
    {
        if (!$this->strings->contains($text)) {
            $this->strings->add($text);
        }

        return $this;
    }


    public function removeString(ListableTextField $text): self
    {
        $this->strings->removeElement($text);

        return $this;
    }

    public function getValue(): string
    {
        $value = $this->getStrings() ?? [];

        if ($value instanceof \Traversable) {
            $value = iterator_to_array($value, false);
        }

        return implode(PHP_EOL, array_map(static fn ($c) => (string) $c, $value));
    }
}
