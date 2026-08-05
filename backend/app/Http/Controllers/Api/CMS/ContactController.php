<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\CMS;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    use HasApiResponse;

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Normally save to database or send email
        Log::info('Contact message received', $validated);

        return $this->success(null, 'Your message has been sent successfully.');
    }
}
