<?php

namespace App\Entity\Participant;

use App\Entity\Account\Contact;
use App\Entity\Capture\CaptureElement;
use App\Entity\Interface\TenantAwareInterface;
use App\Entity\Tenant\User;
use App\Entity\Trait\TenantAwareTrait;
use App\Repository\ParticipantRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipantRoleRepository::class)]
class ParticipantRole implements TenantAwareInterface
{
    use TenantAwareTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $internal = null;

    #[ORM\OneToMany(targetEntity: CaptureElement::class, mappedBy: 'validator')]
    private Collection $validatorCaptureElements;

    #[ORM\OneToMany(targetEntity: CaptureElement::class, mappedBy: 'contributor')]
    private Collection $contributorCaptureElements;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'participantRoles')]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Contact::class, mappedBy: 'participantRoles')]
    private Collection $contacts;

    public function __construct()
    {
        $this->validatorCaptureElements = new ArrayCollection();
        $this->contributorCaptureElements = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->contacts = new ArrayCollection();
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

    public function isInternal(): ?bool
    {
        return $this->internal;
    }

    public function setInternal(bool $internal): static
    {
        $this->internal = $internal;

        return $this;
    }

    public function getValidatorCaptureElements(): Collection
    {
        return $this->validatorCaptureElements;
    }

    public function getContributorCaptureElements(): Collection
    {
        return $this->contributorCaptureElements;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addParticipantRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeParticipantRole($this);
        }

        return $this;
    }

    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): static
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->addParticipantRole($this);
        }

        return $this;
    }

    public function removeContact(Contact $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeParticipantRole($this);
        }

        return $this;
    }

    public function addValidatorCaptureElement(CaptureElement $validatorCaptureElement): static
    {
        if (!$this->validatorCaptureElements->contains($validatorCaptureElement)) {
            $this->validatorCaptureElements->add($validatorCaptureElement);
            $validatorCaptureElement->setValidator($this);
        }

        return $this;
    }

    public function removeValidatorCaptureElement(CaptureElement $validatorCaptureElement): static
    {
        if ($this->validatorCaptureElements->removeElement($validatorCaptureElement)) {
            // set the owning side to null (unless already changed)
            if ($validatorCaptureElement->getValidator() === $this) {
                $validatorCaptureElement->setValidator(null);
            }
        }

        return $this;
    }

    public function addContributorCaptureElement(CaptureElement $contributorCaptureElement): static
    {
        if (!$this->contributorCaptureElements->contains($contributorCaptureElement)) {
            $this->contributorCaptureElements->add($contributorCaptureElement);
            $contributorCaptureElement->setContributor($this);
        }

        return $this;
    }

    public function removeContributorCaptureElement(CaptureElement $contributorCaptureElement): static
    {
        if ($this->contributorCaptureElements->removeElement($contributorCaptureElement)) {
            // set the owning side to null (unless already changed)
            if ($contributorCaptureElement->getContributor() === $this) {
                $contributorCaptureElement->setContributor(null);
            }
        }

        return $this;
    }
}
