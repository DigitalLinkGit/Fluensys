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

    #[ORM\ManyToMany(targetEntity: SystemComponent::class, cascade: ['persist'])]
    private Collection $components;

    public function __construct()
    {
        $this->components = new ArrayCollection();
    }

    public function getComponents(): Collection
    {
        if (!isset($this->components)) {
            $this->components = new ArrayCollection();
        }
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

    public function getValue(): mixed
    {
        return $this->getComponents();
    }
}
