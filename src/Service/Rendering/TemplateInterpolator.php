<?php

namespace App\Service\Rendering;

use Twig\Environment;

final class TemplateInterpolator
{
    public function __construct(private Environment $twig)
    {
    }

    /**
     * Convert placeholders like [MY_VAR] into Twig variables {{ MY_VAR }}.
     */
    public function normalizeToTwig(string $raw): string
    {
        // TODO: getFieldValueByFieldName()
        $result = preg_replace('/\[(\w+)\]/', '{{ $1 }}', $raw);

        return $result ?? $raw;
    }

    public function renderString(string $twigString, array $context): string
    {
        $tpl = $this->twig->createTemplate($twigString);

        return $tpl->render($context);
    }
}
