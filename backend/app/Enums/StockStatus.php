<?php

declare(strict_types=1);

namespace App\Enums;

enum StockStatus: string
{
    case InStock    = 'in_stock';
    case LowStock   = 'low_stock';
    case OutOfStock = 'out_of_stock';

    public function label(): string
    {
        return match($this) {
            self::InStock    => 'In Stock',
            self::LowStock   => 'Low Stock',
            self::OutOfStock => 'Out of Stock',
        };
    }

    public static function fromQuantity(int $quantity, int $lowThreshold = 5): self
    {
        return match(true) {
            $quantity <= 0              => self::OutOfStock,
            $quantity <= $lowThreshold  => self::LowStock,
            default                     => self::InStock,
        };
    }

    /** @return string[] */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
