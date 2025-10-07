<?php
namespace App\Enum;

use App\Entity\Capture\CaptureElement\FlexCaptureElement;
use App\Entity\Capture\CaptureElement\SystemComponentCaptureElement;

enum CaptureElementType: string
{
    case FLEX = 'flex';
    case SYSTEM_COMPONENTS = 'system_components';

    public function label(): string
    {
        return match ($this) {
            self::FLEX => 'Elément de capture libre',
            self::SYSTEM_COMPONENTS => 'Composants du système d\'information',
        };
    }

    public function className(): string
    {
        return match ($this) {
            self::FLEX => FlexCaptureElement::class,
            self::SYSTEM_COMPONENTS => SystemComponentCaptureElement::class,
        };
    }

    /** Symfony ChoiceType : [label => value] */
    public static function choices(): array
    {
        $out = [];
        foreach (self::cases() as $case) {
            $out[$case->label()] = $case->value;
        }
        return $out;
    }
}
