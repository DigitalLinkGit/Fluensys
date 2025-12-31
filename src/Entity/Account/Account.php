<?php

namespace App\Entity\Account;

use App\Entity\Capture\Capture;
use App\Entity\Tenant\TenantAwareInterface;
use App\Entity\Tenant\TenantAwareTrait;
use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account implements TenantAwareInterface
{
    use TenantAwareTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Contact>
     */
    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'account', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $contacts;

    #[ORM\OneToOne(inversedBy: 'account')]
    #[ORM\JoinColumn(name: 'information_system_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?InformationSystem $informationSystem = null;

    /**
     * @var Collection<int, Capture>
     */
    #[ORM\OneToMany(targetEntity: Capture::class, mappedBy: 'account')]
    private Collection $captures;

    #[ORM\ManyToOne(targetEntity: Contact::class)]
    private ?Contact $defaultContact = null;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->captures = new ArrayCollection();
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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): static
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->setAccount($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): static
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getAccount() === $this) {
                $contact->setAccount(null);
            }
        }

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
            $capture->setAccount($this);
        }

        return $this;
    }

    public function removeCapture(Capture $capture): static
    {
        if ($this->captures->removeElement($capture)) {
            // set the owning side to null (unless already changed)
            if ($capture->getAccount() === $this) {
                $capture->setAccount(null);
            }
        }

        return $this;
    }

    public function getDefaultContact(): ?Contact
    {
        return $this->defaultContact;
    }

    public function setDefaultContact(?Contact $defaultContact): static
    {
        $this->defaultContact = $defaultContact;

        return $this;
    }

}
