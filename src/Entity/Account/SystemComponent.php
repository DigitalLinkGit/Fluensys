<?php

namespace App\Entity\Account;

use App\Enum\SystemComponentType;
use App\Repository\SystemComponentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SystemComponentRepository::class)]
class SystemComponent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(enumType: SystemComponentType::class)]
    private ?SystemComponentType $type = null;

    #[ORM\ManyToOne(inversedBy: 'systemComponents')]
    #[ORM\JoinColumn(nullable: true)]
    private ?InformationSystem $informationSystem = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?SystemComponentType
    {
        return $this->type;
    }

    public function setType(SystemComponentType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getInformationSystem(): ?InformationSystem
    {
        return $this->informationSystem;
    }

    public function setInformationSystem(?InformationSystem $informationSystem): static
    {
        $this->informationSystem = $informationSystem;

        return $this;
    }

    public function __toString()
    {
        return $this->name.' ('.$this->type->value.')';
    }
}
