<?php

namespace App\Entity\Capture\Rendering;

use App\Entity\Capture\CaptureElement\CaptureElement;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'text' =>TextChapter::class,
])]
abstract class Chapter
{
    public function __clone()
    {
        $this->id = null;
        $clonedTitle = clone $this->title;
        $this->setTitle($clonedTitle);
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Title::class, cascade: ['persist', 'remove'],fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Title $title = null;

    #[ORM\OneToOne(mappedBy: 'chapter', cascade: ['persist', 'remove'])]
    private ?CaptureElement $captureElement = null;

    abstract public function getTemplateContent(): mixed;

    abstract public function getRenderContent(): mixed;

    abstract public function getFormat(): string;

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
