<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Cart;

use App\DTOs\Cart\AddToCartDTO;
use App\DTOs\Cart\UpdateCartDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Services\CartService;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class CartController extends Controller
{
    use HasApiResponse;

    public function __construct(
        private readonly CartService $cartService,
        private readonly CartRepositoryInterface $cartRepository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $cartItems = $this->cartRepository->getUserCart($request->user()->id);
        
        $subtotal = $cartItems->sum('subtotal');
        
        return $this->success([
            'items' => $cartItems, // In real app, map to CartResource
            'summary' => [
                'subtotal' => $subtotal,
                'total' => $subtotal, // Tax/shipping handled in checkout
            ]
        ], 'Cart retrieved successfully.');
    }

    public function store(AddToCartRequest $request): JsonResponse
    {
        try {
            $this->cartService->addToCart($request->user()->id, AddToCartDTO::fromArray($request->validated()));
            return $this->created(null, 'Item added to cart.');
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $item = $this->cartRepository->find($id);
        
        if (!$item || $item->user_id !== $request->user()->id) {
            return $this->notFound('Cart item not found.');
        }

        $this->cartRepository->delete($id);
        
        return $this->noContent('Item removed from cart.');
    }
    
    public function clear(Request $request): JsonResponse
    {
        $this->cartRepository->clearUserCart($request->user()->id);
        return $this->noContent('Cart cleared.');
    }
}
