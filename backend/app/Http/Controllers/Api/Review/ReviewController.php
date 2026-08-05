<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    use HasApiResponse;

    public function index(Request $request): JsonResponse
    {
        $reviews = Review::with('product')->where('user_id', $request->user()->id)->get();
        return $this->success($reviews, 'Reviews retrieved successfully.');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId = $request->user()->id;

        // Check if user has purchased the product
        $hasPurchased = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.user_id', $userId)
            ->where('order_items.product_id', $validated['product_id'])
            ->exists();

        if (!$hasPurchased) {
            return $this->error('You can only review products you have purchased.', 403);
        }

        // Check if already reviewed
        $existingReview = Review::where('user_id', $userId)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingReview) {
            return $this->error('You have already reviewed this product.', 400);
        }

        $review = Review::create([
            'user_id' => $userId,
            'product_id' => $validated['product_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => true, // Auto approve for now
        ]);

        return $this->created($review, 'Review submitted successfully.');
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $review = Review::where('user_id', $request->user()->id)->find($id);

        if (!$review) {
            return $this->notFound('Review not found.');
        }

        $review->delete();

        return $this->noContent('Review deleted successfully.');
    }
}
