<?php

declare(strict_types=1);

namespace App\DTOs\Product;

readonly class CreateProductDTO
{
    public function __construct(
        public string $name,
        public ?int $categoryId,
        public ?int $brandId,
        public string $description,
        public ?string $shortDescription,
        public float $price,
        public ?float $salePrice,
        public int $stock,
        public bool $isFeatured = false,
        public bool $isActive = true,
        public ?string $sku = null,
        public ?string $metaTitle = null,
        public ?string $metaDescription = null,
        public array $images = [],
        public array $variants = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            categoryId: $data['category_id'] ?? null,
            brandId: $data['brand_id'] ?? null,
            description: $data['description'],
            shortDescription: $data['short_description'] ?? null,
            price: (float) $data['price'],
            salePrice: isset($data['sale_price']) ? (float) $data['sale_price'] : null,
            stock: (int) $data['stock'],
            isFeatured: (bool) ($data['is_featured'] ?? false),
            isActive: (bool) ($data['is_active'] ?? true),
            sku: $data['sku'] ?? null,
            metaTitle: $data['meta_title'] ?? null,
            metaDescription: $data['meta_description'] ?? null,
            images: $data['images'] ?? [],
            variants: $data['variants'] ?? [],
        );
    }
}
