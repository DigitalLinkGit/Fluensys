<?php

namespace App\Entity\Rendering;
use App\Entity\CaptureElement;

class TextChapterRenderer implements ChapterRenderInterface
{
    public function render(CaptureElement $element): Chapter
    {
        return new Chapter(
            new ChapterContent(
                value: $this->buildContent($element),
                format: $this->getFormat()
            )
        );
    }

    public function buildContent(CaptureElement $element): string
    {
        $lines = [];
        foreach ($element->getFields() as $field) {
            $lines[] = $field->getExternalLabel() . ' : ______';
        }

        return implode("\n", $lines);
    }

    public function getFormat(): string
    {
        return 'text';
    }
}
