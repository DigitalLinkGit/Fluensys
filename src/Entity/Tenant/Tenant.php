<?php

namespace App\Entity\Tenant;

use App\Entity\RenderingConfig;
use App\Repository\TenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TenantRepository::class)]
class Tenant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?RenderingConfig $renderingConfig = null;


    public function __construct()
    {
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

    public function getRenderingConfig(): ?RenderingConfig
    {
        return $this->renderingConfig;
    }

    public function setRenderingConfig(?RenderingConfig $renderingConfig): static
    {
        $this->renderingConfig = $renderingConfig;

        return $this;
    }
}
