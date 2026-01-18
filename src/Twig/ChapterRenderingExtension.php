<?php

namespace App\Twig;

use App\Entity\Capture\Rendering\Chapter;
use App\Service\Rendering\ChapterRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ChapterRenderingExtension extends AbstractExtension
{
    public function __construct(private readonly ChapterRenderer $chapterRenderer)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_chapter', [$this, 'renderChapter'], ['is_safe' => ['html']]),
        ];
    }

    public function renderChapter(?Chapter $chapter): string
    {
        if (null === $chapter) {
            return '';
        }

        return $this->chapterRenderer->render($chapter);
    }
}
