<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentCartRepository extends BaseRepository implements CartRepositoryInterface
{
    public function __construct(Cart $model)
    {
        parent::__construct($model);
    }

    public function getUserCart(int $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->with(['product.images', 'variant'])
            ->get();
    }

    public function clearUserCart(int $userId): bool
    {
        return $this->model->where('user_id', $userId)->delete() > 0;
    }

    public function findItem(int $userId, int $productId, ?int $variantId = null): ?Cart
    {
        $query = $this->model
            ->where('user_id', $userId)
            ->where('product_id', $productId);

        if ($variantId) {
            $query->where('variant_id', $variantId);
        } else {
            $query->whereNull('variant_id');
        }

        return $query->first();
    }
}
