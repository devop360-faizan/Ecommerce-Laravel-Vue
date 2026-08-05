<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

interface CartRepositoryInterface extends BaseRepositoryInterface
{
    public function getUserCart(int $userId): \Illuminate\Database\Eloquent\Collection;
    public function clearUserCart(int $userId): bool;
    public function findItem(int $userId, int $productId, ?int $variantId = null): ?\App\Models\Cart;
}
