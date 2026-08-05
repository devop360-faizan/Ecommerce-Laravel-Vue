<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HasApiResponse;

    public function index(Request $request): JsonResponse
    {
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->where('is_active', true);
            }])
            ->get();
            
        return $this->success($categories, 'Categories retrieved successfully.');
    }

    public function show(string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->with(['products' => function($q) {
                $q->where('is_active', true)->limit(10);
            }])
            ->firstOrFail();

        return $this->success($category, 'Category details retrieved.');
    }
}
