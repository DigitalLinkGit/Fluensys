<?php

namespace App\Entity;

use App\Repository\ChapterRendererRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChapterRendererRepository::class)]
interface ChapterRenderInterface
{
    public function render(CaptureElement $element): Chapter;

    public function buildContent(CaptureElement $element): string;

    public function getFormat(): string;

}
