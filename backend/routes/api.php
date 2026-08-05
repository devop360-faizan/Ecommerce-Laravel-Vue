<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Shop\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Cart\CartController;
use App\Http\Controllers\Api\Shop\OrderController;
use App\Http\Controllers\Api\Shop\StripeWebhookController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

Route::prefix('shop')->group(function () {
    Route::get('/products', [\App\Http\Controllers\Api\Shop\ProductController::class, 'index']);
    Route::get('/products/{slug}', [\App\Http\Controllers\Api\Shop\ProductController::class, 'show']);
    Route::get('/categories', [\App\Http\Controllers\Api\Shop\CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [\App\Http\Controllers\Api\Shop\CategoryController::class, 'show']);
    Route::get('/brands', [\App\Http\Controllers\Api\Shop\BrandController::class, 'index']);
    Route::get('/brands/{slug}', [\App\Http\Controllers\Api\Shop\BrandController::class, 'show']);
    Route::get('/search', [\App\Http\Controllers\Api\Shop\SearchController::class, 'index']);
});

Route::prefix('cms')->group(function () {
    Route::get('/pages/{slug}', [\App\Http\Controllers\Api\CMS\PageController::class, 'show']);
    Route::get('/banners', [\App\Http\Controllers\Api\CMS\BannerController::class, 'index']);
    Route::post('/contact', [\App\Http\Controllers\Api\CMS\ContactController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('cart')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Cart\CartController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\Cart\CartController::class, 'store']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\Cart\CartController::class, 'destroy']);
        Route::delete('/', [\App\Http\Controllers\Api\Cart\CartController::class, 'clear']);
        Route::post('/coupon', [\App\Http\Controllers\Api\Cart\CouponController::class, 'apply']);
    });

    Route::prefix('orders')->group(function () {
        Route::post('/', [\App\Http\Controllers\Api\Shop\OrderController::class, 'store']);
    });

    Route::prefix('addresses')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Address\AddressController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\Address\AddressController::class, 'store']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\Address\AddressController::class, 'destroy']);
    });

    Route::prefix('wishlists')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Wishlist\WishlistController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\Wishlist\WishlistController::class, 'store']);
        Route::delete('/{product_id}', [\App\Http\Controllers\Api\Wishlist\WishlistController::class, 'destroy']);
    });

    Route::prefix('reviews')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Review\ReviewController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\Review\ReviewController::class, 'store']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\Review\ReviewController::class, 'destroy']);
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Notification\NotificationController::class, 'index']);
        Route::get('/unread', [\App\Http\Controllers\Api\Notification\NotificationController::class, 'unread']);
        Route::patch('/{id}/read', [\App\Http\Controllers\Api\Notification\NotificationController::class, 'markAsRead']);
    });

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard/stats', [\App\Http\Controllers\Api\Admin\AdminDashboardController::class, 'stats']);
        Route::apiResource('categories', \App\Http\Controllers\Api\Admin\AdminCategoryController::class);
        Route::apiResource('products', \App\Http\Controllers\Api\Admin\AdminProductController::class);
        Route::apiResource('brands', \App\Http\Controllers\Api\Admin\AdminBrandController::class);
        
        Route::get('/orders', [\App\Http\Controllers\Api\Admin\AdminOrderController::class, 'index']);
        Route::get('/orders/{id}', [\App\Http\Controllers\Api\Admin\AdminOrderController::class, 'show']);
        Route::patch('/orders/{id}/status', [\App\Http\Controllers\Api\Admin\AdminOrderController::class, 'updateStatus']);
        
        Route::get('/users', [\App\Http\Controllers\Api\Admin\AdminUserController::class, 'index']);
        Route::patch('/users/{id}/toggle-active', [\App\Http\Controllers\Api\Admin\AdminUserController::class, 'toggleActive']);
    });
});

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);
