<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Shop;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    use HasApiResponse;

    public function handle(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id ?? null;

            if ($orderId) {
                $order = Order::find($orderId);
                if ($order) {
                    $order->update(['payment_status' => PaymentStatus::Paid, 'status' => OrderStatus::Processing]);
                    
                    $payment = Payment::where('transaction_id', $session->id)->first();
                    if ($payment) {
                        $payment->update([
                            'status' => PaymentStatus::Paid,
                            'paid_at' => now(),
                            'gateway_response' => 'checkout.session.completed',
                        ]);
                    }
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}
