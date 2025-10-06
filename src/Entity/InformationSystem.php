<?php

namespace App\Entity;

use App\Entity\Account\Account;
use App\Repository\InformationSystemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InformationSystemRepository::class)]
class InformationSystem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(mappedBy: 'informationSystem', cascade: ['persist', 'remove'])]
    private ?Account $account = null;

    /**
     * @var Collection<int, SystemComponent>
     */
    #[ORM\OneToMany(targetEntity: SystemComponent::class, mappedBy: 'informationSystem', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $systemComponents;

    public function __construct()
    {
        $this->systemComponents = new ArrayCollection();
    }


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
    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return Collection<int, SystemComponent>
     */
    public function getSystemComponents(): Collection
    {
        return $this->systemComponents;
    }

    public function addSystemComponent(SystemComponent $systemComponent): static
    {
        if (!$this->systemComponents->contains($systemComponent)) {
            $this->systemComponents->add($systemComponent);
            $systemComponent->setInformationSystem($this);
        }

        return $this;
    }

    public function removeSystemComponent(SystemComponent $systemComponent): static
    {
        if ($this->systemComponents->removeElement($systemComponent)) {
            // set the owning side to null (unless already changed)
            if ($systemComponent->getInformationSystem() === $this) {
                $systemComponent->setInformationSystem(null);
            }
        }

        return $this;
    }
}
