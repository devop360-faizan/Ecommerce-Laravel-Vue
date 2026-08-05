<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use Exception;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckoutSession(Order $order): Session
    {
        $lineItems = [];

        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => config('services.stripe.currency', 'usd'),
                    'product_data' => [
                        'name' => $item->product_name . ($item->variant_name ? ' - ' . $item->variant_name : ''),
                    ],
                    'unit_amount' => (int) ($item->unit_price * 100), // Stripe expects cents
                ],
                'quantity' => $item->quantity,
            ];
        }

        if ($order->shipping_amount > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => config('services.stripe.currency', 'usd'),
                    'product_data' => [
                        'name' => 'Shipping',
                    ],
                    'unit_amount' => (int) ($order->shipping_amount * 100),
                ],
                'quantity' => 1,
            ];
        }

        if ($order->tax_amount > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => config('services.stripe.currency', 'usd'),
                    'product_data' => [
                        'name' => 'Tax',
                    ],
                    'unit_amount' => (int) ($order->tax_amount * 100),
                ],
                'quantity' => 1,
            ];
        }
        
        // Handle discounts in Stripe by passing coupons or adjusting line items. 
        // For simplicity here, we add a negative line item if there's a discount, 
        // but Stripe might prefer coupons.
        // Actually, negative line items aren't allowed in Stripe. We should use Stripe Coupons, 
        // or just pass the total amount if we are using PaymentIntents instead of Checkout.
        // Let's adjust unit prices or create a custom Checkout Session. 
        // Since we want robust Stripe, we'll use Checkout Session.

        $sessionData = [
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => config('app.frontend_url') . '/checkout/success?session_id={CHECKOUT_SESSION_ID}&order_id=' . $order->id,
            'cancel_url' => config('app.frontend_url') . '/checkout/cancel?order_id=' . $order->id,
            'client_reference_id' => (string) $order->id,
            'metadata' => [
                'order_id' => $order->id,
            ],
        ];

        // Stripe doesn't allow negative line items for discount.
        // If there's a discount, we either create a Stripe Coupon on the fly or just use Stripe Payment Intents directly.
        // To keep it simple and correct, let's use Payment Intents or adjust the items proportionally.
        // Actually, let's just create a Stripe Coupon on the fly and attach it if there's a discount.

        if ($order->discount_amount > 0) {
            $coupon = \Stripe\Coupon::create([
                'amount_off' => (int) ($order->discount_amount * 100),
                'currency' => config('services.stripe.currency', 'usd'),
                'duration' => 'once',
            ]);
            $sessionData['discounts'] = [['coupon' => $coupon->id]];
        }

        return Session::create($sessionData);
    }
}
