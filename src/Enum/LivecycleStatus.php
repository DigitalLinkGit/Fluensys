<?php

namespace App\Enum;

enum LivecycleStatus: string
{
    case TEMPLATE = 'template';
    case DRAFT = 'draft';
    case READY = 'ready';
    case COLLECTING = 'collecting';
    case PENDING = 'pending';
    case SUBMITTED = 'submitted';
    case VALIDATED = 'validated';

    public function getLabel(): string
    {
        return match ($this) {
            self::TEMPLATE => 'Template',
            self::DRAFT => 'Draft',
            self::READY => 'Ready',
            self::COLLECTING => 'Collecting',
            self::PENDING => 'Pending',
            self::SUBMITTED => 'Submitted',
            self::VALIDATED => 'Validated',
        };
    }

    /**
     * @return array<string, string> label => value
     */
    public static function getChoices(): array
    {
        $choices = [];
        foreach (self::cases() as $case) {
            $choices[$case->getLabel()] = $case->value;
        }

        return $choices;
    }
}
