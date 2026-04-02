<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\ExamSchedule;
use Carbon\Carbon;

class GuruDashboardComposer
{
    public function compose(View $view): void
    {
        if (!Auth::check() || Auth::user()->role !== 'guru') {
            $view->with('upcomingExams', collect());
            return;
        }

        $user = Auth::user();

        // Upcoming exams for this guru
        try {
            $today = Carbon::today();
            $upcoming = ExamSchedule::with(['subject', 'kelas'])
                ->where('start_time', '>=', $today)
                ->where('is_published', true)
                ->orderBy('start_time')
                ->limit(9)
                ->get();
        } catch (\Throwable $e) {
            $upcoming = collect();
        }

        $view->with('upcomingExams', $upcoming);
    }
}
