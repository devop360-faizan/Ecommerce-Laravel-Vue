<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending  = 'pending';
    case Paid     = 'paid';
    case Failed   = 'failed';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match($this) {
            self::Pending  => 'Pending',
            self::Paid     => 'Paid',
            self::Failed   => 'Failed',
            self::Refunded => 'Refunded',
        };
    }

    public function isPaid(): bool
    {
        return $this === self::Paid;
    }

    /** @return string[] */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
