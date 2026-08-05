<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

readonly class UpdateProfileDTO
{
    public function __construct(
        public string $name,
        public ?string $phone = null,
        public ?string $avatar = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            phone: $data['phone'] ?? null,
            avatar: $data['avatar'] ?? null,
        );
    }
}
