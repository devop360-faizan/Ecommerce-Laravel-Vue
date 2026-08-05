<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getActiveProducts(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function findBySlug(string $slug): ?Product;
    public function getFeatured(int $limit = 10): \Illuminate\Database\Eloquent\Collection;
}
