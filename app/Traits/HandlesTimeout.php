<?php

namespace App\Traits;

use App\Services\TimeoutService;

trait HandlesTimeout
{
    /**
     * Set timeout for long-running operations
     *
     * @param string $operation
     * @return void
     */
    protected function setOperationTimeout(string $operation = 'default'): void
    {
        TimeoutService::setExecutionTime($operation);
    }
    
    /**
     * Check if operation should continue
     *
     * @param int $startTime
     * @param int $maxDuration
     * @return bool
     */
    protected function shouldContinueOperation(int $startTime, int $maxDuration = 60): bool
    {
        return !TimeoutService::isTimeoutApproaching($startTime, $maxDuration);
    }
    
    /**
     * Get operation progress information
     *
     * @param int $startTime
     * @param int $total
     * @param int $current
     * @return array
     */
    protected function getOperationProgress(int $startTime, int $total, int $current): array
    {
        $elapsed = time() - $startTime;
        $remaining = TimeoutService::getRemainingTime();
        $progress = $total > 0 ? ($current / $total) * 100 : 0;
        
        return [
            'elapsed_time' => $elapsed,
            'remaining_time' => $remaining,
            'progress_percentage' => round($progress, 2),
            'items_processed' => $current,
            'total_items' => $total,
        ];
    }
    
    /**
     * Handle timeout gracefully
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleTimeout(string $message = 'Operation timed out'): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => 'TIMEOUT',
            'execution_time' => time() - $_SERVER['REQUEST_TIME'],
        ], 408); // Request Timeout
    }
}
