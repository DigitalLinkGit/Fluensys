<?php

// src/Service/ConditionToggler.php

namespace App\Service\Helper;

use App\Entity\Capture\Condition;
use App\Enum\CaptureElementStatus;

final class ConditionToggler
{
    /**
     * @param iterable<Condition> $conditions
     */
    public function apply(iterable $conditions): void
    {
        foreach ($conditions as $condition) {
            $this->applyOne($condition);
        }
    }

    public function applyOne(Condition $condition): void
    {
        $target = $condition->getTargetElement();
        $field = $condition->getSourceField();

        // 0) imcomplete condition
        if (!$target || !$field) {
            return;
        }

        $expected = $condition->getExpectedValue();
        $actual = $field->getValue();

        // 1) Not answered = inactive
        if (null === $actual || '' === $actual) {
            $target->setActive(false);
            return;
        }

        // 2) EQUALS (string trim)
        $actualNorm = $this->norm($actual);
        $expectedNorm = $this->toStr($expected);

        if (is_array($actualNorm)) {
            $isActive = in_array($expectedNorm, $actualNorm, true);
        } else {
            $isActive = $actualNorm === $expectedNorm;
        }

        $target->setActive($isActive);
        /* a PENDING status should never be active (missing participantRole or condition) */
        if (CaptureElementStatus::PENDING === $target->getStatus()) {
            $target->setActive(false);
        }
    }

    private function norm(mixed $v): string|array
    {
        if (is_array($v)) {
            // Normalize each element, drop empty values, keep unique
            $out = [];
            foreach ($v as $item) {
                $s = $this->toStr($item);
                if ('' !== $s) {
                    $out[] = $s;
                }
            }

            return array_values(array_unique($out));
        }

        return $this->toStr($v);
    }

    private function toStr(mixed $v): string
    {
        return trim((string) $v);
    }
}
