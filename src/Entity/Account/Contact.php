<?php

namespace App\Entity\Account;

use App\Entity\Participant\ParticipantRole;
use App\Entity\Tenant\TenantAwareInterface;
use App\Entity\Tenant\TenantAwareTrait;
use App\Entity\Truc;
use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact implements TenantAwareInterface
{
    use TenantAwareTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $function = null;

    #[ORM\ManyToOne(inversedBy: 'contacts')]
    private ?Account $account = null;

    #[ORM\ManyToMany(targetEntity: ParticipantRole::class, inversedBy: 'contacts')]
    private Collection $participantRoles;


    public function __construct()
    {
        $this->participantRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
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

    public function getFunction(): ?string
    {
        return $this->function;
    }

    public function setFunction(string $function): static
    {
        $this->function = $function;

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
     * @return Collection<int, ParticipantRole>
     */
    public function getParticipantRoles(): Collection
    {
        return $this->participantRoles;
    }

    public function addParticipantRole(ParticipantRole $participantRole): static
    {
        if (!$this->participantRoles->contains($participantRole)) {
            $this->participantRoles->add($participantRole);
        }

        return $this;
    }

    public function removeParticipantRole(ParticipantRole $participantRole): static
    {
        $this->participantRoles->removeElement($participantRole);

        return $this;
    }

}
