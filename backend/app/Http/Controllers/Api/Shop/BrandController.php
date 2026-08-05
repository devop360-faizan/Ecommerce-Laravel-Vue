<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    use HasApiResponse;

    public function index(Request $request): JsonResponse
    {
        $brands = Brand::where('is_active', true)->get();
        return $this->success($brands, 'Brands retrieved successfully.');
    }

    public function show(string $slug): JsonResponse
    {
        $brand = Brand::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return $this->success($brand, 'Brand details retrieved.');
    }
}
