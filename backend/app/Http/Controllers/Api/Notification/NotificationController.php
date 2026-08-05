<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Notification;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use HasApiResponse;

    public function index(Request $request): JsonResponse
    {
        // Using Laravel built-in notifications
        $notifications = $request->user()->notifications()->paginate(15);
        return $this->paginated($notifications, 'Notifications retrieved.');
    }
    
    public function unread(Request $request): JsonResponse
    {
        $notifications = $request->user()->unreadNotifications;
        return $this->success($notifications, 'Unread notifications retrieved.');
    }

    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return $this->success($notification, 'Notification marked as read.');
    }
}
