<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentMethod: string
{
    case COD      = 'cod';
    case Stripe   = 'stripe';
    case Razorpay = 'razorpay';

    public function label(): string
    {
        return match($this) {
            self::COD      => 'Cash on Delivery',
            self::Stripe   => 'Stripe',
            self::Razorpay => 'Razorpay',
        };
    }

    public function requiresOnlineProcessing(): bool
    {
        return $this !== self::COD;
    }

    /** @return string[] */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
