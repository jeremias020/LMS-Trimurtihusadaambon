<?php

namespace App\Http\Controllers;

use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $notifications = SystemNotification::where('user_id', auth()->id())
            ->with(['user'])
            ->latest()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function unreadCount(): JsonResponse
    {
        $count = SystemNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function recent(): JsonResponse
    {
        $notifications = SystemNotification::where('user_id', auth()->id())
            ->unread()
            ->latest()
            ->take(5)
            ->get(['id', 'title', 'message', 'type', 'action_url', 'created_at']);

        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'action_url' => $notification->action_url,
                    'time_ago' => $notification->time_ago,
                ];
            })
        ]);
    }

    public function markAsRead(SystemNotification $notification): JsonResponse
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(): JsonResponse
    {
        SystemNotification::where('user_id', auth()->id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    public function delete(SystemNotification $notification): JsonResponse
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }
}
