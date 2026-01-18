<?php

namespace App\Entity\Capture\Field;

use App\Entity\Interface\UploadableField;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class FileField extends Field implements UploadableField
{
    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $value = null;

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $path = null;

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): static
    {
        $this->path = $path;

        return $this;
    }
}
