<?php

declare(strict_types=1);

namespace App\DTOs\Order;

use App\Enums\PaymentMethod;

readonly class PlaceOrderDTO
{
    public function __construct(
        public int $addressId,
        public PaymentMethod $paymentMethod,
        public ?string $couponCode = null,
        public ?string $notes = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            addressId: (int) $data['address_id'],
            paymentMethod: PaymentMethod::from($data['payment_method']),
            couponCode: $data['coupon_code'] ?? null,
            notes: $data['notes'] ?? null,
        );
    }
}
