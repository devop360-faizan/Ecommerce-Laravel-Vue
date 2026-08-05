<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HasApiResponse;

    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $products = $this->productRepository->getActiveProducts(
            $request->all(),
            (int) $request->get('per_page', 15)
        );

        return $this->paginated(ProductResource::collection($products), 'Products retrieved.');
    }

    public function show(string $slug): JsonResponse
    {
        $product = $this->productRepository->findBySlug($slug);

        if (! $product) {
            return $this->notFound('Product not found.');
        }

        $product->increment('views_count');

        // Can use a separate DetailedResource here in real app
        return $this->success(new ProductResource($product), 'Product details retrieved.');
    }
}
