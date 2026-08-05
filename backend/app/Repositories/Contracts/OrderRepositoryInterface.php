<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function getUserOrders(int $userId, int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator;
    public function findByOrderNumber(string $orderNumber): ?\App\Models\Order;
}
