<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function getTree(): \Illuminate\Database\Eloquent\Collection;
    public function findBySlug(string $slug): ?\App\Models\Category;
}
