<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    use HasApiResponse;

    public function index(Request $request): JsonResponse
    {
        $query = $request->input('q', '');
        
        if (empty($query)) {
            return $this->success([], 'Empty search.');
        }

        $products = Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%");
            })
            ->with(['category', 'brand'])
            ->paginate(15);

        return $this->paginated($products, 'Search results.');
    }
}
