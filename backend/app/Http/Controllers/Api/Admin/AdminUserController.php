<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    use HasApiResponse;

    public function index(Request $request): JsonResponse
    {
        $users = User::paginate(15);
        return $this->paginated($users, 'Users retrieved.');
    }

    public function toggleActive(int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        
        if ($user->role === \App\Enums\UserRole::Admin) {
            return $this->error('Cannot deactivate admin users.', 403);
        }

        $user->update(['is_active' => !$user->is_active]);

        return $this->success($user, 'User status toggled.');
    }
}
