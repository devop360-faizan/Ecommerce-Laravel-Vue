<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\Order\PlaceOrderDTO;
use App\Enums\PaymentStatus;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly CartRepositoryInterface $cartRepository
    ) {}

    public function placeOrder(User $user, PlaceOrderDTO $dto): Order
    {
        $cartItems = $this->cartRepository->getUserCart($user->id);

        if ($cartItems->isEmpty()) {
            throw new Exception("Cart is empty.");
        }

        $address = Address::where('user_id', $user->id)->findOrFail($dto->addressId);

        return DB::transaction(function () use ($user, $dto, $cartItems, $address) {
            $subtotal = $cartItems->sum('subtotal');
            $shippingAmount = 0.0;
            $taxAmount = 0.0;
            $discountAmount = 0.0;

            if ($dto->couponCode) {
                $coupon = Coupon::where('code', $dto->couponCode)->first();
                if ($coupon && $coupon->isValidFor($user, $subtotal)) {
                    $discountAmount = $coupon->discount_type->calculate($subtotal, $coupon->discount_value);
                }
            }

            $total = ($subtotal - $discountAmount) + $shippingAmount + $taxAmount;

            /** @var Order $order */
            $order = $this->orderRepository->create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'payment_method' => $dto->paymentMethod,
                'subtotal' => $subtotal,
                'shipping_amount' => $shippingAmount,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'coupon_code' => $dto->couponCode,
                'notes' => $dto->notes,
                'shipping_address_snapshot' => $address->toArray(),
            ]);

            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'product_name' => $item->product->name,
                    'variant_name' => $item->variant?->name,
                    'product_thumbnail' => $item->product->thumbnail,
                    'unit_price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ]);

                // Reduce stock
                $product = $item->product;
                if ($item->variant_id) {
                    $item->variant->decrement('stock', $item->quantity);
                } else {
                    $product->decrement('stock', $item->quantity);
                }
                $product->increment('sales_count', $item->quantity);
            }

            $order->payment()->create([
                'payment_method' => $dto->paymentMethod,
                'amount' => $total,
                'status' => PaymentStatus::Pending,
            ]);

            if ($dto->couponCode) {
                $coupon = Coupon::where('code', $dto->couponCode)->first();
                if ($coupon) {
                    $coupon->increment('used_count');
                    $coupon->usages()->create([
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                    ]);
                }
            }

            $this->cartRepository->clearUserCart($user->id);

            return $order;
        });
    }
}
