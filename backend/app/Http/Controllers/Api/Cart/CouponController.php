<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Cart;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Exceptions\InvalidCouponException;

class CouponController extends Controller
{
    use HasApiResponse;

    public function __construct(
        private readonly CartRepositoryInterface $cartRepository
    ) {}

    public function apply(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|exists:coupons,code',
        ]);

        $coupon = Coupon::where('code', $validated['code'])->first();
        $user = $request->user();
        
        $cartItems = $this->cartRepository->getUserCart($user->id);
        if ($cartItems->isEmpty()) {
            return $this->error('Cart is empty.', 400);
        }
        
        $subtotal = $cartItems->sum('subtotal');

        if (!$coupon->isValidFor($user, $subtotal)) {
            return $this->error('Coupon is invalid or expired.', 400);
        }

        $discount = $coupon->discount_type->calculate($subtotal, $coupon->discount_value);

        return $this->success([
            'code' => $coupon->code,
            'discount' => $discount,
            'new_total' => $subtotal - $discount
        ], 'Coupon applied successfully.');
    }
}
