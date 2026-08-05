<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentMethod: string
{
    case Stripe = 'stripe';

    public function label(): string
    {
        return match($this) {
            self::Stripe => 'Stripe',
        };
    }

    public function requiresOnlineProcessing(): bool
    {
        return true;
    }

    /** @return string[] */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
