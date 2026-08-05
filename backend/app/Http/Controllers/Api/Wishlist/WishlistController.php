<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Wishlist;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    use HasApiResponse;

    public function index(Request $request): JsonResponse
    {
        $wishlist = Wishlist::with('product')->where('user_id', $request->user()->id)->get();
        return $this->success($wishlist, 'Wishlist retrieved successfully.');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $wishlist = Wishlist::firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $validated['product_id'],
        ]);

        return $this->created($wishlist, 'Product added to wishlist.');
    }

    public function destroy(Request $request, int $productId): JsonResponse
    {
        Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->delete();

        return $this->noContent('Product removed from wishlist.');
    }
}
