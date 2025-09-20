<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Get notifications for the current user
            $notifications = Notification::with(['sender'])
                ->where(function($query) use ($user) {
                    $query->where('penerima_id', $user->id)
                          ->orWhere('tipe_penerima', 'semua');
                })
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Count unread notifications
            $unreadCount = Notification::where(function($query) use ($user) {
                $query->where('penerima_id', $user->id)
                      ->orWhere('tipe_penerima', 'semua');
            })
            ->whereNull('read_at')
            ->count();

            $view->with([
                'notifications' => $notifications,
                'unreadCount' => $unreadCount
            ]);
        } else {
            $view->with([
                'notifications' => collect(),
                'unreadCount' => 0
            ]);
        }
    }
}
