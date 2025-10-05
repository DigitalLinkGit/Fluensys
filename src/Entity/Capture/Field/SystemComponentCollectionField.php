<?php

namespace App\Entity\Capture\Field;

use App\Entity\SystemComponent;
use App\Repository\SystemComponentCollectionFieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass: SystemComponentCollectionFieldRepository::class)]
final class SystemComponentCollectionField extends Field
{
    public const TYPE = 'system_component_collection';
    private Collection $components;

    public function __construct(iterable $components = [])
    {
        $this->components = new ArrayCollection();
        foreach ($components as $component) {
            $this->addComponent($component);
        }
    }
    public function getComponents(): Collection
    {
        return $this->components;
    }

    public function addComponent(SystemComponent $component): self
    {
        if (!$this->components->contains($component)) {
            $this->components->add($component);
        }
        return $this;
    }

    public function removeComponent(SystemComponent $component): self
    {
        $this->components->removeElement($component);
        return $this;
    }

    public function getValue(): mixed
    {
        // TODO: Implement getValue() method.
        return "null";
    }
}
