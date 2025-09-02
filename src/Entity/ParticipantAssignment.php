<?php

namespace App\Entity;

use App\Entity\ParticipantRole;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ParticipantAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\ManyToOne(targetEntity: ParticipantRole::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ParticipantRole $role;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $externalLastName = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $externalFirstName = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $externalEmail = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $externalFunction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExternalLastName(): ?string
    {
        return $this->externalLastName;
    }

    public function setExternalLastName(?string $externalLastName): static
    {
        $this->externalLastName = $externalLastName;

        return $this;
    }

    public function getExternalFirstName(): ?string
    {
        return $this->externalFirstName;
    }

    public function setExternalFirstName(?string $externalFirstName): static
    {
        $this->externalFirstName = $externalFirstName;

        return $this;
    }

    public function getExternalEmail(): ?string
    {
        return $this->externalEmail;
    }

    public function setExternalEmail(?string $externalEmail): static
    {
        $this->externalEmail = $externalEmail;

        return $this;
    }

    public function getExternalFunction(): ?string
    {
        return $this->externalFunction;
    }

    public function setExternalFunction(?string $externalFunction): static
    {
        $this->externalFunction = $externalFunction;

        return $this;
    }


    public function getRole(): ?ParticipantRole
    {
        return $this->role;
    }

    public function setRole(?ParticipantRole $role): static
    {
        $this->role = $role;

        return $this;
    }

}
