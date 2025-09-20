<?php

namespace App\Console\Commands;

use App\Models\PracticeSchedule;
use App\Jobs\SendPracticeScheduleNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendPracticeNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'practice:send-notifications 
                            {type=all : Type of notifications to send (all, reminder, today)}
                            {--force : Force send notifications even if already sent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send practice schedule notifications to teachers and students';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');
        $force = $this->option('force');
        
        $this->info('Starting practice schedule notifications...');
        
        $sent = 0;
        
        try {
            // Send reminder notifications for tomorrow's practices
            if ($type === 'all' || $type === 'reminder') {
                $sent += $this->sendReminderNotifications($force);
            }
            
            // Send today's practice notifications
            if ($type === 'all' || $type === 'today') {
                $sent += $this->sendTodayNotifications($force);
            }
            
            $this->info("Notifications sent successfully. Total: {$sent}");
            
            Log::info('Practice notifications command completed', [
                'type' => $type,
                'sent' => $sent,
                'forced' => $force
            ]);
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Failed to send notifications: ' . $e->getMessage());
            Log::error('Practice notifications command failed', [
                'error' => $e->getMessage(),
                'type' => $type
            ]);
            
            return 1;
        }
    }
    
    /**
     * Send reminder notifications for tomorrow's practices
     */
    private function sendReminderNotifications(bool $force = false): int
    {
        $this->info('Sending reminder notifications for tomorrow\'s practices...');
        
        $query = PracticeSchedule::with(['practical', 'teacher'])
                    ->tomorrow()
                    ->where('status', 'scheduled');
        
        if (!$force) {
            $query->where('notification_sent', false);
        }
        
        $schedules = $query->get();
        
        $this->info("Found {$schedules->count()} schedules for tomorrow");
        
        foreach ($schedules as $schedule) {
            $this->line("Sending reminder for: {$schedule->title}");
            
            // Dispatch job to send notification
            SendPracticeScheduleNotification::dispatch($schedule, 'reminder');
        }
        
        return $schedules->count();
    }
    
    /**
     * Send notifications for today's practices
     */
    private function sendTodayNotifications(bool $force = false): int
    {
        $this->info('Sending notifications for today\'s practices...');
        
        $query = PracticeSchedule::with(['practical', 'teacher'])
                    ->today()
                    ->where('status', 'scheduled')
                    ->where('start_time', '>=', Carbon::now()->addHours(2)->format('H:i:s')); // Send 2 hours before
        
        if (!$force) {
            $query->where('notification_sent', false);
        }
        
        $schedules = $query->get();
        
        $this->info("Found {$schedules->count()} schedules for today");
        
        foreach ($schedules as $schedule) {
            $this->line("Sending today notification for: {$schedule->title}");
            
            // Dispatch job to send notification
            SendPracticeScheduleNotification::dispatch($schedule, 'today');
        }
        
        return $schedules->count();
    }
}
