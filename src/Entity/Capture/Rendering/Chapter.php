<?php

namespace App\Entity\Capture\Rendering;

use App\Entity\Capture\CaptureElement;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Chapter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Title::class, cascade: ['persist', 'remove'], fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Title $title = null;

    #[ORM\OneToOne(mappedBy: 'chapter', cascade: ['persist', 'remove'])]
    private ?CaptureElement $captureElement = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $content = null;

    public function __clone()
    {
        $this->id = null;
        $clonedTitle = null !== $this->title ? clone $this->title : null;
        if ($clonedTitle) {
            $this->setTitle($clonedTitle);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?Title
    {
        return $this->title;
    }

    public function setTitle(Title $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCaptureElement(): ?CaptureElement
    {
        return $this->captureElement;
    }

    public function setCaptureElement(?CaptureElement $captureElement): static
    {
        if (null === $captureElement && null !== $this->captureElement) {
            $this->captureElement->setChapter(null);
        }

        if (null !== $captureElement && $captureElement->getChapter() !== $this) {
            $captureElement->setChapter($this);
        }

        $this->captureElement = $captureElement;

        return $this;
    }

    /**
     * Template content edited by the user (placeholders like [VAR]).
     */
    public function getTemplateContent(): ?string
    {
        return $this->content;
    }

    public function setTemplateContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Kept for backward compatibility with existing code.
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Kept for compatibility; single format for now.
     */
    public function getFormat(): string
    {
        return 'text';
    }
}
