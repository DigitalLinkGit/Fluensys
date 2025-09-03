<?php

namespace App\Entity;

use App\Entity\Rendering\TextChapter;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'text' =>TextChapter::class,
])]
abstract class Chapter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $level = null;

    #[ORM\OneToOne(mappedBy: 'chapter', cascade: ['persist', 'remove'])]
    private ?CaptureElement $captureElement = null;

    abstract public function getTemplateContent(): mixed;

    abstract public function buildContent(CaptureElement $element): mixed;

    abstract public function getFormat(): string;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getCaptureElement(): ?CaptureElement
    {
        return $this->captureElement;
    }

    public function setCaptureElement(?CaptureElement $captureElement): static
    {
        // unset the owning side of the relation if necessary
        if ($captureElement === null && $this->captureElement !== null) {
            $this->captureElement->setChapter(null);
        }

        // set the owning side of the relation if necessary
        if ($captureElement !== null && $captureElement->getChapter() !== $this) {
            $captureElement->setChapter($this);
        }

        $this->captureElement = $captureElement;

        return $this;
    }

}
