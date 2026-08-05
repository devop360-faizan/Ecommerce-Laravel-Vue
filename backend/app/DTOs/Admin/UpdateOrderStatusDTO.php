<?php

declare(strict_types=1);

namespace App\DTOs\Admin;

use App\Enums\OrderStatus;

readonly class UpdateOrderStatusDTO
{
    public function __construct(
        public OrderStatus $status,
        public ?string $note = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            status: OrderStatus::from($data['status']),
            note: $data['note'] ?? null,
        );
    }
}
