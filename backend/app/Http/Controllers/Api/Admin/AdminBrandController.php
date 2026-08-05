<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBrandController extends Controller
{
    use HasApiResponse;

    public function index(Request $request): JsonResponse
    {
        $brands = Brand::all();
        return $this->success($brands, 'Brands retrieved.');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $brand = Brand::create($validated);

        return $this->created($brand, 'Brand created.');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $brand = Brand::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $brand->update($validated);
        return $this->success($brand, 'Brand updated.');
    }

    public function destroy(int $id): JsonResponse
    {
        Brand::findOrFail($id)->delete();
        return $this->noContent('Brand deleted.');
    }
}
