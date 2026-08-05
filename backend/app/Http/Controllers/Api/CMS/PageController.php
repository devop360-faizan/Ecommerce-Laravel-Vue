<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\CMS;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    use HasApiResponse;

    public function show(string $slug): JsonResponse
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return $this->success($page, 'Page retrieved successfully.');
    }
}
