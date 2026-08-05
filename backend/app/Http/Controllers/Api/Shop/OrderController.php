<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\DTOs\Order\PlaceOrderDTO;
use App\Http\Requests\Order\PlaceOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    use HasApiResponse;

    public function __construct(
        private readonly OrderService $orderService,
        private readonly PaymentService $paymentService
    ) {}

    public function store(PlaceOrderRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            $dto = PlaceOrderDTO::fromArray($request->validated());

            $order = $this->orderService->placeOrder($user, $dto);

            // Strict Stripe Checkout Integration
            $session = $this->paymentService->createCheckoutSession($order);

            // Save the session ID in the payment meta
            $order->payment->update([
                'meta' => ['stripe_session_id' => $session->id],
                'transaction_id' => $session->id,
            ]);

            return $this->created([
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_url' => $session->url,
            ], 'Order placed successfully. Redirect to payment.');
            
        } catch (Exception $e) {
            Log::error('Order placement failed: ' . $e->getMessage());
            return $this->error($e->getMessage());
        }
    }
}
