<?php

namespace App\Entity\Field;

use App\Repository\DateFieldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DateFieldRepository::class)]
class DateField extends Field
{
    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $value = null;

    public function getValue(): ?\DateTimeInterface
    {
        return $this->value;
    }

    public function setValue(?\DateTimeInterface $value): static
    {
        $this->value = $value;

        return $this;
    }
}
