<?php

namespace App\Service\Rendering;

use App\Entity\Capture\Field\ChecklistField;
use App\Entity\Capture\Field\DateField;
use App\Entity\Capture\Field\DecimalField;
use App\Entity\Capture\Field\ListableField;
use App\Entity\Capture\Field\TableField;
use App\Entity\Capture\Field\UrlField;
use App\Entity\Capture\Rendering\Chapter;

class ChapterRenderer
{
    public function render(Chapter $chapter): string
    {
        $template = (string) $chapter->getTemplateContent();
        $element = $chapter->getCaptureElement();

        if (null === $element) {
            return $this->escapeText($template);
        }

        $fields = $element->getFields();
        $fieldMap = [];
        foreach ($fields as $field) {
            $key = mb_strtoupper((string) $field->getTechnicalName());
            $fieldMap[$key] = $field;
        }

        $rendered = preg_replace_callback('/\[([A-Za-z0-9_]+)]/u', function (array $m) use ($fieldMap) {
            $key = mb_strtoupper($m[1]);

            if (!array_key_exists($key, $fieldMap)) {
                return $m[0];
            }

            $field = $fieldMap[$key];
            $html = $this->renderFieldAsHtml($field);

            if ('' === trim(strip_tags($html))) {
                return $m[0];
            }

            return $html;
        }, $template);

        return $rendered ?? $this->escapeText($template);
    }

    private function renderFieldAsHtml(object $field): string
    {
        if ($field instanceof TableField) {
            return $this->renderTableField($field);
        }

        /*if ($field instanceof ImageField) {
            return $this->renderImageField($field);
        }*/

        if ($field instanceof DateField) {
            return $this->renderDateField($field);
        }

        if ($field instanceof DecimalField) {
            return $this->renderDecimalField($field);
        }

        if ($field instanceof ChecklistField) {
            return $this->renderChecklistField($field);
        }

        if ($field instanceof ListableField) {
            return $this->renderListableField($field);
        }

        if ($field instanceof UrlField) {
            return $this->renderUrlField($field);
        }

        return $this->renderScalarFallback($field);
    }

    private function renderScalarFallback(object $field): string
    {
        $value = method_exists($field, 'getValue') ? $field->getValue() : null;

        if (is_bool($value)) {
            return $this->renderBoolean($value);
        }

        if (is_array($value)) {
            // In this project: arrays are string lists (multi-select etc.)
            return $this->renderStringListInline($value);
        }

        if (is_object($value)) {
            return method_exists($value, '__toString') ? $this->escapeText((string) $value) : '';
        }

        if (null === $value) {
            return '';
        }

        return $this->escapeText((string) $value);
    }

    private function renderDecimalField(DecimalField $field): string
    {
        $value = $field->getValue();

        if (null === $value || '' === $value) {
            return '';
        }

        if (!is_numeric($value)) {
            return $this->escapeText((string) $value);
        }

        // Example: 2 decimals max, trim trailing zeros
        $formatted = rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.');

        return $this->escapeText($formatted);
    }

    private function renderDateField(DateField $field): string
    {
        $value = $field->getValue();

        if (!$value instanceof \DateTimeInterface) {
            return '';
        }

        return $this->escapeText($value->format('d/m/Y'));
    }

    private function renderListableField(ListableField $field): string
    {
        $value = $field->getValue();

        return $this->renderStringListBullets($value);
    }

    private function renderChecklistField(ChecklistField $field): string
    {
        $value = $field->getValue();

        // ChecklistField value is always a string array.
        if (!is_array($value) || [] === $value) {
            return '';
        }

        if (true === $field->isUniqueResponse()) {
            $items = $this->normalizeStringList($value);
            if ([] === $items) {
                return '';
            }

            // Unique response: render the first selected value as plain text
            return $this->escapeText($items[0]);
        }

        // Multiple: render bullets
        return $this->renderStringListBullets($value);
    }

    private function renderStringListInline(mixed $value): string
    {
        if (!is_array($value) || [] === $value) {
            return '';
        }

        $items = $this->normalizeStringList($value);
        if ([] === $items) {
            return '';
        }

        return $this->escapeText(implode(', ', $items));
    }

    private function renderStringListBullets(mixed $value): string
    {
        if (!is_array($value) || [] === $value) {
            return '';
        }

        $items = $this->normalizeStringList($value);
        if ([] === $items) {
            return '';
        }

        $html = '<ul class="doc-list">';
        foreach ($items as $item) {
            $html .= '<li>'.$this->escapeText($item).'</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    private function renderUrlField(UrlField $field): string
    {
        $value = $field->getValue();

        if (!is_string($value)) {
            return '';
        }

        $url = trim($value);
        if ('' === $url) {
            return '';
        }

        // Basic normalization: allow users to type "example.com"
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://'.$url;
        }

        // Security: only allow http(s)
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (!in_array(strtolower((string) $scheme), ['http', 'https'], true)) {
            return $this->escapeText($value);
        }

        $href = $this->escapeAttribute($url);
        $label = $this->escapeText($value);

        return '<a class="doc-link" href="'.$href.'" target="_blank" rel="noopener noreferrer">'.$label.'</a>';
    }

    private function normalizeStringList(array $value): array
    {
        $items = [];

        foreach ($value as $v) {
            if (is_scalar($v)) {
                $s = trim((string) $v);
                if ('' !== $s) {
                    $items[] = $s;
                }
                continue;
            }

            if (is_object($v) && method_exists($v, '__toString')) {
                $s = trim((string) $v);
                if ('' !== $s) {
                    $items[] = $s;
                }
            }
        }

        return $items;
    }

    private function renderBoolean(bool $value): string
    {
        return $this->escapeText($value ? 'vrai' : 'faux');
    }

    private function renderTableField(object $field): string
    {
        // Case 1: your actual model (TableField with columns + rows as entities)
        if ($field instanceof TableField) {
            $columns = method_exists($field, 'getColumns') ? $field->getColumns() : null;
            $rows = method_exists($field, 'getRows') ? $field->getRows() : null;

            if (null === $rows || 0 === $rows->count()) {
                return '';
            }

            // Build ordered column definitions
            $colDefs = [];
            if (null !== $columns && $columns->count() > 0) {
                foreach ($columns as $col) {
                    // Expecting TableFieldColumn with getKey/getLabel/getPosition
                    $colDefs[] = [
                        'key' => method_exists($col, 'getKey') ? (string) $col->getKey() : null,
                        'label' => method_exists($col, 'getLabel') ? (string) $col->getLabel() : '',
                        'position' => method_exists($col, 'getPosition') ? (int) $col->getPosition() : 0,
                    ];
                }

                usort($colDefs, static fn ($a, $b) => $a['position'] <=> $b['position']);
            }

            // Fallback: infer columns from first row values keys if no columns configured
            if ([] === $colDefs) {
                $firstRow = $rows->first();
                if (false !== $firstRow && method_exists($firstRow, 'getValues')) {
                    $values = $firstRow->getValues();
                    if (is_array($values)) {
                        foreach (array_keys($values) as $k) {
                            $colDefs[] = ['key' => (string) $k, 'label' => (string) $k, 'position' => 0];
                        }
                    }
                }
            }

            if ([] === $colDefs) {
                return '';
            }

            $html = '<table class="doc-table">';

            // Header
            $html .= '<thead><tr>';
            foreach ($colDefs as $c) {
                $html .= '<th>'.$this->escapeText($c['label']).'</th>';
            }
            $html .= '</tr></thead>';

            // Body
            $html .= '<tbody>';
            foreach ($rows as $row) {
                if (!is_object($row) || !method_exists($row, 'getValues')) {
                    continue;
                }

                $values = $row->getValues();
                if (!is_array($values)) {
                    $values = [];
                }

                $html .= '<tr>';
                foreach ($colDefs as $c) {
                    $cell = $values[$c['key']] ?? '';
                    $html .= '<td>'.$this->escapeCell($cell).'</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';

            return $html;
        }

        // Case 2: legacy array-based formats (keep your existing support)
        $headers = method_exists($field, 'getHeaders') ? $field->getHeaders() : null;
        $rows = method_exists($field, 'getRows') ? $field->getRows() : null;

        if (null === $rows && method_exists($field, 'getValue')) {
            $value = $field->getValue();

            if (is_array($value) && isset($value['rows']) && is_array($value['rows'])) {
                $rows = $value['rows'];
                if (null === $headers && isset($value['headers']) && is_array($value['headers'])) {
                    $headers = $value['headers'];
                }
            } elseif (is_array($value)) {
                $rows = $value;
            }
        }

        if (!is_array($rows) || [] === $rows) {
            return '';
        }

        if (null === $headers) {
            $first = $rows[0] ?? null;
            if (is_array($first) && $this->isAssocArray($first)) {
                $headers = array_keys($first);
            }
        }

        $html = '<table class="doc-table">';

        if (is_array($headers) && [] !== $headers) {
            $html .= '<thead><tr>';
            foreach ($headers as $h) {
                $html .= '<th>'.$this->escapeText((string) $h).'</th>';
            }
            $html .= '</tr></thead>';
        }

        $html .= '<tbody>';
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $html .= '<tr>';

            if (is_array($headers) && [] !== $headers && $this->isAssocArray($row)) {
                foreach ($headers as $h) {
                    $html .= '<td>'.$this->escapeCell($row[$h] ?? '').'</td>';
                }
            } else {
                foreach ($row as $cell) {
                    $html .= '<td>'.$this->escapeCell($cell).'</td>';
                }
            }

            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        return $html;
    }

    private function renderImageField(object $field): string
    {
        if (!method_exists($field, 'getValue')) {
            return '';
        }

        $value = $field->getValue();
        if (!is_string($value) || '' === trim($value)) {
            return '';
        }

        $src = $this->escapeAttribute($value);

        return '<img class="doc-image" src="'.$src.'" alt="" />';
    }

    private function escapeText(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function escapeAttribute(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function escapeCell(mixed $cell): string
    {
        if ($cell instanceof \DateTimeInterface) {
            return $this->escapeText($cell->format('Y-m-d'));
        }
        if (is_bool($cell)) {
            return $this->escapeText($cell ? 'true' : 'false');
        }
        if (is_scalar($cell)) {
            return $this->escapeText((string) $cell);
        }
        if (is_object($cell) && method_exists($cell, '__toString')) {
            return $this->escapeText((string) $cell);
        }

        return '';
    }

    private function isAssocArray(array $arr): bool
    {
        $keys = array_keys($arr);

        return $keys !== array_keys($keys);
    }
}
