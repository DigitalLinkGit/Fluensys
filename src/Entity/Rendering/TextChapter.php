<?php

namespace App\Entity\Rendering;

use App\Entity\CaptureElement;
use App\Repository\TextChapterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TextChapterRepository::class)]
class TextChapter extends Chapter
{

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $content = null;

    public function getTemplateContent(): ?string
    {
        return $this->content;
    }

    public function setTemplateContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }


    public function buildContent(CaptureElement $element): mixed
    {
        // TODO: Implement buildContent() method.
        return $this->content;
    }

    public function getFormat(): string
    {
        return 'text';
    }
}
