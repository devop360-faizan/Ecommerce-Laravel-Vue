<?php

declare(strict_types=1);

namespace App\DTOs\Address;

readonly class AddressDTO
{
    public function __construct(
        public string $label,
        public string $fullName,
        public string $phone,
        public string $addressLine1,
        public ?string $addressLine2,
        public string $city,
        public string $state,
        public string $country,
        public string $postalCode,
        public bool $isDefault = false,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            label: $data['label'],
            fullName: $data['full_name'],
            phone: $data['phone'],
            addressLine1: $data['address_line1'],
            addressLine2: $data['address_line2'] ?? null,
            city: $data['city'],
            state: $data['state'],
            country: $data['country'],
            postalCode: $data['postal_code'],
            isDefault: (bool) ($data['is_default'] ?? false),
        );
    }
}
