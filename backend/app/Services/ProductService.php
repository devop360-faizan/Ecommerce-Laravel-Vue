<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\Product\CreateProductDTO;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function createProduct(CreateProductDTO $dto): Product
    {
        $data = [
            'name' => $dto->name,
            'category_id' => $dto->categoryId,
            'brand_id' => $dto->brandId,
            'description' => $dto->description,
            'short_description' => $dto->shortDescription,
            'price' => $dto->price,
            'sale_price' => $dto->salePrice,
            'stock' => $dto->stock,
            'is_featured' => $dto->isFeatured,
            'is_active' => $dto->isActive,
            'sku' => $dto->sku,
            'meta_title' => $dto->metaTitle,
            'meta_description' => $dto->metaDescription,
        ];

        /** @var Product $product */
        $product = $this->productRepository->create($data);

        // Process images and variants logic could go here

        return $product;
    }
}
