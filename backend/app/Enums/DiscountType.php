<?php

declare(strict_types=1);

namespace App\Enums;

enum DiscountType: string
{
    case Percentage = 'percentage';
    case Fixed      = 'fixed';

    public function label(): string
    {
        return match($this) {
            self::Percentage => 'Percentage',
            self::Fixed      => 'Fixed Amount',
        };
    }

    public function calculate(float $amount, float $discount): float
    {
        return match($this) {
            self::Percentage => round($amount * ($discount / 100), 2),
            self::Fixed      => min($discount, $amount),
        };
    }

    /** @return string[] */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
