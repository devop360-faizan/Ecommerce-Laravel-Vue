<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use HasApiResponse;

    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register(RegisterDTO::fromArray($request->validated()));
        $token = $user->createToken('web')->plainTextToken;

        return $this->created([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Registration successful.');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(LoginDTO::fromArray($request->validated()));

        return $this->success([
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ], 'Login successful.');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->noContent('Logged out successfully.');
    }

    public function me(Request $request): JsonResponse
    {
        return $this->success(new UserResource($request->user()), 'User profile retrieved.');
    }
}
