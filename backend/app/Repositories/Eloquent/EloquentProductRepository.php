<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function getActiveProducts(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->where('is_active', true)
            ->with(['category', 'brand', 'images'])
            ->filter($filters) // Uses the Filterable trait
            ->paginate($perPage);
    }

    public function findBySlug(string $slug): ?Product
    {
        return $this->model
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'brand', 'images', 'variants'])
            ->first();
    }

    public function getFeatured(int $limit = 10): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->where('is_featured', true)
            ->with(['images'])
            ->limit($limit)
            ->get();
    }
}
