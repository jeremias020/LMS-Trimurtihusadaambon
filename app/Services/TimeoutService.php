<?php

namespace App\Services;

class TimeoutService
{
    /**
     * Set execution time limit for specific operations
     *
     * @param string $operation
     * @return void
     */
    public static function setExecutionTime(string $operation = 'default'): void
    {
        $timeouts = config('timeout.operations', []);
        $defaultTimeout = config('timeout.max_execution_time', 300);
        
        $timeout = $timeouts[$operation] ?? $defaultTimeout;
        
        // Set execution time limit
        set_time_limit($timeout);
        
        // Increase memory limit if needed
        $memoryLimit = config('timeout.memory_limit', '512M');
        ini_set('memory_limit', $memoryLimit);
    }
    
    /**
     * Reset execution time to default
     *
     * @return void
     */
    public static function resetExecutionTime(): void
    {
        set_time_limit(config('timeout.max_execution_time', 300));
    }
    
    /**
     * Check if operation is taking too long
     *
     * @param int $startTime
     * @param int $maxDuration
     * @return bool
     */
    public static function isTimeoutApproaching(int $startTime, int $maxDuration = 60): bool
    {
        return (time() - $startTime) > $maxDuration;
    }
    
    /**
     * Get remaining execution time
     *
     * @return int
     */
    public static function getRemainingTime(): int
    {
        $maxTime = ini_get('max_execution_time');
        if ($maxTime == 0) {
            return -1; // Unlimited
        }
        
        $elapsed = time() - $_SERVER['REQUEST_TIME'];
        return max(0, $maxTime - $elapsed);
    }
}
