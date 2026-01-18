<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class JsonDecodeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('json_decode', [$this, 'jsonDecode']),
        ];
    }

    public function jsonDecode(?string $value, bool $assoc = true): mixed
    {
        if (null === $value || '' === $value) {
            return $assoc ? [] : null;
        }

        $decoded = json_decode($value, $assoc);
        return is_array($decoded) ? $decoded : ($assoc ? [] : null);
    }
}
