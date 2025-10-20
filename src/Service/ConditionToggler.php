<?php

// src/Service/ConditionToggler.php

namespace App\Service;

use App\Entity\Capture\Condition;

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
        $isActive = $this->toStr($actual) === $this->toStr($expected);
        $target->setActive($isActive);
    }

    private function toStr(mixed $v): string
    {
        return trim((string) $v);
    }
}
