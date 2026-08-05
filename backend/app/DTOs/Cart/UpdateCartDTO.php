<?php

declare(strict_types=1);

namespace App\DTOs\Cart;

readonly class UpdateCartDTO
{
    public function __construct(
        public int $quantity,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(quantity: (int) $data['quantity']);
    }
}
