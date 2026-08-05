<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    use HasApiResponse;

    public function index(Request $request): JsonResponse
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return $this->success($categories, 'Categories retrieved successfully.');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer|exists:categories,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category = Category::create($validated);

        return $this->created($category, 'Category created successfully.');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'parent_id' => 'nullable|integer|exists:categories,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return $this->success($category, 'Category updated successfully.');
    }

    public function destroy(int $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        // Ensure no products or subcategories depend on it, or handle recursively
        $category->delete();

        return $this->noContent('Category deleted successfully.');
    }
}
