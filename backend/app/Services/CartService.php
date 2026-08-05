<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\Cart\AddToCartDTO;
use App\Exceptions\InsufficientStockException;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;

class CartService
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepository,
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function addToCart(int $userId, AddToCartDTO $dto): Cart
    {
        /** @var Product $product */
        $product = $this->productRepository->findOrFail($dto->productId);
        
        $stock = clone $product->stock;
        $price = clone $product->final_price;

        if ($dto->variantId) {
            $variant = ProductVariant::where('product_id', $product->id)->findOrFail($dto->variantId);
            $stock = clone $variant->stock;
            $price = $variant->price ?? $price;
        }

        if ($stock < $dto->quantity) {
            throw new InsufficientStockException($product->name, $stock);
        }

        $existingItem = $this->cartRepository->findItem($userId, $dto->productId, $dto->variantId);

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $dto->quantity;
            if ($stock < $newQuantity) {
                throw new InsufficientStockException($product->name, $stock);
            }

            $this->cartRepository->update($existingItem->id, ['quantity' => $newQuantity]);
            return $this->cartRepository->findItem($userId, $dto->productId, $dto->variantId);
        }

        /** @var Cart $cart */
        $cart = $this->cartRepository->create([
            'user_id' => $userId,
            'product_id' => $dto->productId,
            'variant_id' => $dto->variantId,
            'quantity' => $dto->quantity,
            'unit_price' => $price,
        ]);

        return $cart;
    }
}
