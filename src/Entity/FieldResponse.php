<?php

namespace App\Entity;

use App\Repository\FieldResponseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FieldResponseRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'textarea' => TextAreaFieldResponse::class,
    //ex: 'number' => NumberFieldResponse::class
])]
abstract class FieldResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    protected ?Field $field = null;

    abstract public function getValue(): mixed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getField(): ?Field
    {
        return $this->field;
    }

    public function setField(?Field $field): static
    {
        $this->field = $field;

        return $this;
    }
}
