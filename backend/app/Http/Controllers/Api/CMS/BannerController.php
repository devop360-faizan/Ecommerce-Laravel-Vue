<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\CMS;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use HasApiResponse;

    public function index(Request $request): JsonResponse
    {
        $group = $request->query('group', 'home');
        
        $banners = Banner::where('is_active', true)
            ->where('group', $group)
            ->orderBy('sort_order')
            ->get();
            
        return $this->success($banners, 'Banners retrieved successfully.');
    }
}
