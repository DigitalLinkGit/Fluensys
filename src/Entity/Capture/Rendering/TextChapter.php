<?php

namespace App\Entity\Capture\Rendering;

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

    public function getRenderContent(): string
    {
        // ToDO: make a FieldValueResolver
        /*
        pour chaque Field du template text
        récupérer le field et fournir la valeur en fonction du type
        */

        $template = (string) $this->getTemplateContent();

        $fields = $this->getCaptureElement()->getFields();
        $valueMap = [];
        foreach ($fields as $field) {
            $name = mb_strtoupper($field->getTechnicalName());
            $valueMap[$name] = $field->getValue();
        }

        // Remplace [VAR] par la valeur correspondante si elle existe (insensible à la casse)
        $rendered = preg_replace_callback('/\[([A-Za-z0-9_]+)\]/u', function (array $m) use ($valueMap) {
            $key = mb_strtoupper($m[1]);
            if (!array_key_exists($key, $valueMap)) {
                return $m[0]; // si inconnu, on laisse tel quel
            }
            $v = $valueMap[$key];

            // conversions minimales pour éviter "Array" ou erreur d'objet non convertible
            if ($v instanceof \DateTimeInterface) {
                return $v->format('Y-m-d');
            }
            if (is_array($v)) {
                return implode(', ', array_map(fn ($x) => is_scalar($x) ? (string) $x : '', $v));
            }
            if (is_object($v)) {
                return method_exists($v, '__toString') ? (string) $v : '';
            }
            $sv = is_string($v) ? $v : (string) $v;
            if ('' === trim($sv)) {
                return $m[0]; // keep [NOMDELASOCIETE] if no value
            }

            return (string) $v;
        }, $template);

        return $rendered ?? $template;
    }

    public function getFormat(): string
    {
        return 'text';
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }
}
