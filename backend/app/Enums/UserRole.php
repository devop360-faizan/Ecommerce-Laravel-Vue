<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case Customer = 'customer';
    case Admin    = 'admin';

    public function label(): string
    {
        return match($this) {
            self::Customer => 'Customer',
            self::Admin    => 'Administrator',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }
}
