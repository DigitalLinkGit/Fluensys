<?php

namespace App\Entity\Capture\Field;

use App\Entity\Account\SystemComponent;
use App\Repository\SystemComponentCollectionFieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SystemComponentCollectionFieldRepository::class)]
final class SystemComponentCollectionField extends Field
{
    #[ORM\ManyToMany(targetEntity: SystemComponent::class, cascade: ['persist'])]
    private Collection $components;

    public function __construct()
    {
        $this->components = new ArrayCollection();
    }

    public function getComponents(): Collection
    {
        return $this->components;
    }

    public function addComponent(SystemComponent $c): self
    {
        if (!$this->components->contains($c)) {
            $this->components->add($c);
        }

        return $this;
    }

    public function removeComponent(SystemComponent $c): self
    {
        $this->components->removeElement($c);

        return $this;
    }

    public function getValue(): string
    {
        $components = $this->getComponents() ?? [];

        if ($components instanceof \Traversable) {
            $components = iterator_to_array($components, false);
        }

        return implode(PHP_EOL, array_map(static fn ($c) => (string) $c, $components));
    }
}
