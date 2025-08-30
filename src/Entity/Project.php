<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, Capture>
     */
    #[ORM\ManyToMany(targetEntity: Capture::class, inversedBy: 'projects')]
    private Collection $captures;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?bool $template = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: true)]
    private ?InformationSystem $informationSystem = null;

    public function __construct()
    {
        $this->captures = new ArrayCollection();
        $this->template = true;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Capture>
     */
    public function getCaptures(): Collection
    {
        return $this->captures;
    }

    public function addCapture(Capture $capture): static
    {
        if (!$this->captures->contains($capture)) {
            $this->captures->add($capture);
        }

        return $this;
    }

    public function removeCapture(Capture $capture): static
    {
        $this->captures->removeElement($capture);

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function isTemplate(): ?bool
    {
        return $this->template;
    }

    public function setTemplate(bool $template): static
    {
        $this->template = $template;

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
}
