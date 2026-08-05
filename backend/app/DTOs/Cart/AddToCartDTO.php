<?php

declare(strict_types=1);

namespace App\DTOs\Cart;

readonly class AddToCartDTO
{
    public function __construct(
        public int $productId,
        public int $quantity,
        public ?int $variantId = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productId: (int) $data['product_id'],
            quantity: (int) $data['quantity'],
            variantId: isset($data['variant_id']) ? (int) $data['variant_id'] : null,
        );
    }
}
