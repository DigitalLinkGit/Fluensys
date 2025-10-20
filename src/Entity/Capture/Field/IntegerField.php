<?php

namespace App\Entity\Capture\Field;

use App\Repository\IntegerFieldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IntegerFieldRepository::class)]
final class IntegerField extends Field
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?int $value = null;

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): IntegerField
    {
        $this->value = $value;

        return $this;
    }
}
