<?php

namespace App\Jobs;

use App\Models\PracticeSchedule;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendPracticeScheduleNotification implements ShouldQueue
{
    use Queueable;

    protected $schedule;
    protected $notificationType; // 'reminder' or 'today'

    /**
     * Create a new job instance.
     */
    public function __construct(PracticeSchedule $schedule, string $notificationType = 'reminder')
    {
        $this->schedule = $schedule;
        $this->notificationType = $notificationType;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Load relationships
            $this->schedule->load(['practical', 'teacher', 'participants']);

            $title = $this->getNotificationTitle();
            $message = $this->getNotificationMessage();
            $icon = $this->getNotificationIcon();

            // Send notification to teacher
            $this->sendNotificationToUser($this->schedule->teacher, $title, $message, $icon);

            // Send notification to students
            $students = User::where('role', 'siswa')->get();
            foreach ($students as $student) {
                $this->sendNotificationToUser($student, $title, $message, $icon);
            }

            // Mark notification as sent
            $this->schedule->markNotificationSent();

            Log::info('Practice schedule notification sent', [
                'schedule_id' => $this->schedule->id,
                'type' => $this->notificationType
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send practice schedule notification: ' . $e->getMessage());
            throw $e;
        }
    }

    private function sendNotificationToUser(User $user, string $title, string $message, string $icon)
    {
        Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'tipe' => $this->getNotificationType(),
            'icon' => $icon,
            'read_at' => null,
        ]);
    }

    private function getNotificationTitle(): string
    {
        return match($this->notificationType) {
            'reminder' => 'Pengingat: Praktikum Besok',
            'today' => 'Praktikum Hari Ini',
            default => 'Pemberitahuan Praktikum'
        };
    }

    private function getNotificationMessage(): string
    {
        $schedule = $this->schedule;
        $dateFormat = $schedule->practice_date->format('d/m/Y');
        $timeFormat = $schedule->start_time->format('H:i') . ' - ' . $schedule->end_time->format('H:i');
        
        $baseMessage = "Praktikum: {$schedule->title}\n";
        $baseMessage .= "Tanggal: {$dateFormat}\n";
        $baseMessage .= "Waktu: {$timeFormat}\n";
        $baseMessage .= "Guru: {$schedule->teacher->name}";
        
        if ($schedule->location) {
            $baseMessage .= "\nLokasi: {$schedule->location}";
        }

        return $baseMessage;
    }

    private function getNotificationIcon(): string
    {
        return match($this->notificationType) {
            'reminder' => 'clock',
            'today' => 'calendar-check',
            default => 'bell'
        };
    }

    private function getNotificationType(): string
    {
        return match($this->notificationType) {
            'reminder' => 'warning',
            'today' => 'primary',
            default => 'info'
        };
    }
}
