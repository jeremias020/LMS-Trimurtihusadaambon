<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Halaman daftar semua notifikasi.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        $notifications = Notification::forUser($userId)
            ->latest()
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Jumlah notifikasi belum dibaca (AJAX).
     */
    public function unreadCount(): JsonResponse
    {
        $count = Notification::forUser(auth()->id())->unread()->count();

        return response()->json(['count' => $count]);
    }

    /**
     * 5 notifikasi terbaru belum dibaca (AJAX — untuk dropdown header).
     */
    public function recent(): JsonResponse
    {
        $notifications = Notification::forUser(auth()->id())
            ->unread()
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'notifications' => $notifications->map(fn($n) => [
                'id'         => $n->id,
                'title'      => $n->display_title,
                'message'    => $n->display_message,
                'type'       => $n->display_type,
                'icon'       => $n->icon_name,      // nama icon saja, tanpa prefix
                'color'      => $n->color,
                'action_url' => $n->display_action_url,
                'time_ago'   => $n->time_ago,
            ]),
            'unread_count'  => Notification::forUser(auth()->id())->unread()->count(),
        ]);
    }

    /**
     * Tandai satu notifikasi sudah dibaca.
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        if ((int)$notification->penerima_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Tandai semua notifikasi sudah dibaca.
     */
    public function markAllAsRead(): JsonResponse
    {
        Notification::forUser(auth()->id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
                'status'  => 'terbaca',
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Hapus satu notifikasi.
     */
    public function delete(Notification $notification): JsonResponse
    {
        if ((int)$notification->penerima_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }
}
