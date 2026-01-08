<?php

namespace App\Entity;

use App\Entity\Capture\Capture;
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

    /**
     * @var Collection<int, Capture>
     */
    #[ORM\ManyToMany(targetEntity: Capture::class, inversedBy: 'recurringCaptureProjects')]
    private Collection $recurringCapture;

    public function __construct()
    {
        $this->captures = new ArrayCollection();
        $this->recurringCapture = new ArrayCollection();
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

    /**
     * @return Collection<int, Capture>
     */
    public function getRecurringCapture(): Collection
    {
        return $this->recurringCapture;
    }

    public function addRecurringCapture(Capture $recurringCapture): static
    {
        if (!$this->recurringCapture->contains($recurringCapture)) {
            $this->recurringCapture->add($recurringCapture);
        }

        return $this;
    }

    public function removeRecurringCapture(Capture $recurringCapture): static
    {
        $this->recurringCapture->removeElement($recurringCapture);

        return $this;
    }
}
