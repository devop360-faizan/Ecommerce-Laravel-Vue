<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminOrderController extends Controller
{
    use HasApiResponse;

    public function index(Request $request): JsonResponse
    {
        $orders = Order::with(['user', 'items', 'payment'])->latest()->paginate(15);
        return $this->paginated($orders, 'Orders retrieved.');
    }

    public function show(int $id): JsonResponse
    {
        $order = Order::with(['user', 'items', 'payment'])->findOrFail($id);
        return $this->success($order, 'Order details.');
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', Rule::in(OrderStatus::values())],
        ]);

        $order->update(['status' => $validated['status']]);

        return $this->success($order, 'Order status updated.');
    }
}
