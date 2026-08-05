<?php

declare(strict_types=1);

namespace App\DTOs\Coupon;

readonly class ApplyCouponDTO
{
    public function __construct(
        public string $code,
        public float $cartTotal,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            code: strtoupper(trim($data['code'])),
            cartTotal: (float) $data['cart_total'],
        );
    }
}
