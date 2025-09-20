<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TimeoutService;

class TestTimeout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:timeout {operation=default} {--duration=30}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test timeout settings for different operations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $operation = $this->argument('operation');
        $duration = $this->option('duration');
        
        $this->info("Testing timeout for operation: {$operation}");
        $this->info("Duration: {$duration} seconds");
        
        // Set timeout for the operation
        TimeoutService::setExecutionTime($operation);
        
        $startTime = time();
        $this->info("Starting at: " . date('Y-m-d H:i:s'));
        
        // Simulate work
        for ($i = 0; $i < $duration; $i++) {
            if (!$this->shouldContinue($startTime, $duration)) {
                $this->error("Operation timed out at {$i} seconds");
                return 1;
            }
            
            $this->info("Progress: {$i}/{$duration} seconds");
            sleep(1);
        }
        
        $this->info("Operation completed successfully!");
        $this->info("Total execution time: " . (time() - $startTime) . " seconds");
        
        return 0;
    }
    
    /**
     * Check if operation should continue
     */
    private function shouldContinue(int $startTime, int $maxDuration): bool
    {
        $elapsed = time() - $startTime;
        $remaining = TimeoutService::getRemainingTime();
        
        if ($remaining > 0 && $elapsed >= $remaining) {
            return false;
        }
        
        return $elapsed < $maxDuration;
    }
}
