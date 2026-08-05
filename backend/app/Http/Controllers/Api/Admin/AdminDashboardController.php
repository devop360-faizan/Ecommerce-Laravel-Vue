<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    use HasApiResponse;

    public function stats(Request $request): JsonResponse
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();

        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        return $this->success([
            'overview' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'total_users' => $totalUsers,
                'total_products' => $totalProducts,
            ],
            'recent_orders' => $recentOrders,
        ], 'Dashboard stats retrieved.');
    }
}
