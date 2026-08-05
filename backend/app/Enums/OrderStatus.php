<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case Pending    = 'pending';
    case Confirmed  = 'confirmed';
    case Processing = 'processing';
    case Shipped    = 'shipped';
    case Delivered  = 'delivered';
    case Cancelled  = 'cancelled';
    case Refunded   = 'refunded';

    public function label(): string
    {
        return match($this) {
            self::Pending    => 'Pending',
            self::Confirmed  => 'Confirmed',
            self::Processing => 'Processing',
            self::Shipped    => 'Shipped',
            self::Delivered  => 'Delivered',
            self::Cancelled  => 'Cancelled',
            self::Refunded   => 'Refunded',
        };
    }

    public function isCancellable(): bool
    {
        return in_array($this, [self::Pending, self::Confirmed]);
    }

    public function canTransitionTo(self $next): bool
    {
        $allowed = [
            self::Pending->value    => [self::Confirmed, self::Cancelled],
            self::Confirmed->value  => [self::Processing, self::Cancelled],
            self::Processing->value => [self::Shipped],
            self::Shipped->value    => [self::Delivered],
            self::Delivered->value  => [self::Refunded],
            self::Cancelled->value  => [],
            self::Refunded->value   => [],
        ];

        return in_array($next, $allowed[$this->value] ?? []);
    }

    /** @return string[] */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
