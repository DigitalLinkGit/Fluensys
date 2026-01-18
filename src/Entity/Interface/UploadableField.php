<?php

namespace App\Entity\Interface;

interface UploadableField
{
    public function getTechnicalName(): ?string;
    public function getName(): ?string;

    public function getPath(): ?string;
    public function setPath(?string $path): static;

    public function getValue(): mixed;
    public function setValue(?string $value): static;
}
