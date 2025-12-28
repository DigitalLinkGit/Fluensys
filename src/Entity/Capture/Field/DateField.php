<?php

namespace App\Entity\Capture\Field;

use App\Repository\DateFieldRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DateFieldRepository::class)]
final class DateField extends Field
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
