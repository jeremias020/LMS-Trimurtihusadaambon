<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\NotificationComposer;
use App\Http\ViewComposers\GuruStatsComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register view composers
        View::composer('partials.notifications', NotificationComposer::class);
        View::composer('layouts.admin', NotificationComposer::class);
        View::composer('layouts.guru', NotificationComposer::class);
        View::composer('layouts.siswa', NotificationComposer::class);
        
        // Register guru stats composer for sidebar
        View::composer('partials.sidebar-guru', GuruStatsComposer::class);
        View::composer('layouts.guru', GuruStatsComposer::class);
    }
}
