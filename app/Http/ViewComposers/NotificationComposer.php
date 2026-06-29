<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NotificationComposer
{
    /**
     * Bind data notifikasi ke view.
     */
    public function compose(View $view): void
    {
        if (!Auth::check()) {
            $view->with(['notifications' => collect(), 'unreadCount' => 0]);
            return;
        }

        $userId   = Auth::id();
        $cacheKey = 'notif_' . $userId;

        try {
            $notifications = Cache::remember($cacheKey . '_list', 60, function () use ($userId) {
                return Notification::forUser($userId)
                    ->latest()
                    ->limit(10)
                    ->get();
            });

            $unreadCount = Cache::remember($cacheKey . '_unread', 60, function () use ($userId) {
                return Notification::forUser($userId)->unread()->count();
            });
        } catch (\Throwable $e) {
            $notifications = collect();
            $unreadCount   = 0;
        }

        $view->with(compact('notifications', 'unreadCount'));
    }
}
