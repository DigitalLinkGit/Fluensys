<?php

namespace App\Entity\Capture\Field;

use App\Entity\Interface\UploadableField;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ImageField extends Field implements UploadableField
{
    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $value = null;

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $path = null;

    #[ORM\Column(length: 20, options: ['default' => 'medium'])]
    private string $displayMode = 'medium';

    public function getDisplayMode(): string
    {
        return $this->displayMode;
    }

    public function setDisplayMode(string $displayMode): static
    {
        $this->displayMode = $displayMode;
        return $this;
    }


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
