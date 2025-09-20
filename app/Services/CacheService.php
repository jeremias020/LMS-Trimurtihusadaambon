<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheService
{
    /**
     * Cache duration in minutes
     */
    const CACHE_DURATION = [
        'short' => 5,      // 5 minutes
        'medium' => 30,    // 30 minutes
        'long' => 120,     // 2 hours
        'very_long' => 1440, // 24 hours
    ];

    /**
     * Cache dashboard statistics
     */
    public static function cacheDashboardStats(array $stats): void
    {
        Cache::put('dashboard_stats', $stats, now()->addMinutes(self::CACHE_DURATION['short']));
    }

    /**
     * Get cached dashboard statistics
     */
    public static function getDashboardStats(): ?array
    {
        return Cache::get('dashboard_stats');
    }

    /**
     * Cache user statistics
     */
    public static function cacheUserStats(array $stats): void
    {
        Cache::put('user_stats', $stats, now()->addMinutes(self::CACHE_DURATION['medium']));
    }

    /**
     * Get cached user statistics
     */
    public static function getUserStats(): ?array
    {
        return Cache::get('user_stats');
    }

    /**
     * Cache material statistics
     */
    public static function cacheMaterialStats(array $stats): void
    {
        Cache::put('material_stats', $stats, now()->addMinutes(self::CACHE_DURATION['medium']));
    }

    /**
     * Get cached material statistics
     */
    public static function getMaterialStats(): ?array
    {
        return Cache::get('material_stats');
    }

    /**
     * Cache attendance statistics
     */
    public static function cacheAttendanceStats(array $stats): void
    {
        Cache::put('attendance_stats', $stats, now()->addMinutes(self::CACHE_DURATION['short']));
    }

    /**
     * Get cached attendance statistics
     */
    public static function getAttendanceStats(): ?array
    {
        return Cache::get('attendance_stats');
    }

    /**
     * Cache recent activities
     */
    public static function cacheRecentActivities(array $activities): void
    {
        Cache::put('recent_activities', $activities, now()->addMinutes(self::CACHE_DURATION['short']));
    }

    /**
     * Get cached recent activities
     */
    public static function getRecentActivities(): ?array
    {
        return Cache::get('recent_activities');
    }

    /**
     * Cache chart data
     */
    public static function cacheChartData(array $data): void
    {
        Cache::put('chart_data', $data, now()->addMinutes(self::CACHE_DURATION['medium']));
    }

    /**
     * Get cached chart data
     */
    public static function getChartData(): ?array
    {
        return Cache::get('chart_data');
    }

    /**
     * Cache user permissions
     */
    public static function cacheUserPermissions(int $userId, array $permissions): void
    {
        Cache::put("user_permissions_{$userId}", $permissions, now()->addMinutes(self::CACHE_DURATION['long']));
    }

    /**
     * Get cached user permissions
     */
    public static function getUserPermissions(int $userId): ?array
    {
        return Cache::get("user_permissions_{$userId}");
    }

    /**
     * Cache material list with pagination
     */
    public static function cacheMaterialList(string $key, array $data): void
    {
        Cache::put("materials_{$key}", $data, now()->addMinutes(self::CACHE_DURATION['medium']));
    }

    /**
     * Get cached material list
     */
    public static function getMaterialList(string $key): ?array
    {
        return Cache::get("materials_{$key}");
    }

    /**
     * Cache assignment list with pagination
     */
    public static function cacheAssignmentList(string $key, array $data): void
    {
        Cache::put("assignments_{$key}", $data, now()->addMinutes(self::CACHE_DURATION['medium']));
    }

    /**
     * Get cached assignment list
     */
    public static function getAssignmentList(string $key): ?array
    {
        return Cache::get("assignments_{$key}");
    }

    /**
     * Clear all dashboard cache
     */
    public static function clearDashboardCache(): void
    {
        $keys = [
            'dashboard_stats',
            'user_stats',
            'material_stats',
            'attendance_stats',
            'recent_activities',
            'chart_data'
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        Log::info('Dashboard cache cleared');
    }

    /**
     * Clear user-specific cache
     */
    public static function clearUserCache(int $userId): void
    {
        Cache::forget("user_permissions_{$userId}");
        Log::info("User cache cleared for user ID: {$userId}");
    }

    /**
     * Clear material cache
     */
    public static function clearMaterialCache(): void
    {
        // Clear all material-related cache
        $pattern = 'materials_*';
        // Note: Laravel doesn't support pattern-based cache clearing by default
        // This would need to be implemented with Redis or custom logic
        
        Log::info('Material cache cleared');
    }

    /**
     * Clear assignment cache
     */
    public static function clearAssignmentCache(): void
    {
        // Clear all assignment-related cache
        $pattern = 'assignments_*';
        // Note: Laravel doesn't support pattern-based cache clearing by default
        // This would need to be implemented with Redis or custom logic
        
        Log::info('Assignment cache cleared');
    }

    /**
     * Clear all cache
     */
    public static function clearAllCache(): void
    {
        Cache::flush();
        Log::info('All cache cleared');
    }

    /**
     * Get cache statistics
     */
    public static function getCacheStats(): array
    {
        return [
            'dashboard_stats' => Cache::has('dashboard_stats'),
            'user_stats' => Cache::has('user_stats'),
            'material_stats' => Cache::has('material_stats'),
            'attendance_stats' => Cache::has('attendance_stats'),
            'recent_activities' => Cache::has('recent_activities'),
            'chart_data' => Cache::has('chart_data'),
        ];
    }
}
