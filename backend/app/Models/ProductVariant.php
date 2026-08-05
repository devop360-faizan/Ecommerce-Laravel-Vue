<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StockStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'product_id', 'name', 'size', 'color', 'price', 'stock', 'sku', 'is_active'
])]
class ProductVariant extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getStockStatusAttribute(): StockStatus
    {
        return StockStatus::fromQuantity($this->stock);
    }
}
