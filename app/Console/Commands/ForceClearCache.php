<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ForceClearCache extends Command
{
    protected $signature = 'cache:force-clear
                            {--force : Force clear without confirmation}
                            {--clear-logs : Also clear log files}';

    protected $description = 'Clear cache without using database driver';

    public function handle()
    {
        try {
            if (!$this->option('force') && !$this->confirm('Are you sure you want to force clear all cache?')) {
                $this->error('Operation cancelled!');
                return 1;
            }

            $this->info('Starting force cache clear...');

            // 1. Hapus file cache di bootstrap/cache (hanya file cache Laravel umum)
            $knownCacheFiles = [
                'packages.php',
                'services.php',
                'config.php',
                'routes-v7.php',
                'routes.php',
            ];

            foreach ($knownCacheFiles as $file) {
                $path = base_path('bootstrap/cache/' . $file);
                if (file_exists($path) && is_writable($path)) {
                    File::delete($path);
                    $this->info('Removed: /bootstrap/cache/' . $file);
                } elseif (file_exists($path)) {
                    $this->warn('Cannot remove: /bootstrap/cache/' . $file . ' (permission denied)');
                }
            }

            // 2. Clear framework cache
            $this->clearDirectory(storage_path('framework/cache'), 'storage/framework/cache/');

            // 3. Clear compiled views
            $this->clearDirectory(storage_path('framework/views'), 'storage/framework/views/');

            // 4. Clear session files
            $this->clearDirectory(storage_path('framework/sessions'), 'storage/framework/sessions/');

            // 5. Optional: Clear logs
            if ($this->option('clear-logs')) {
                $this->clearDirectory(storage_path('logs'), 'storage/logs/');
            } elseif (!$this->option('force')) {
                if ($this->confirm('Would you like to clear log files as well?')) {
                    $this->clearDirectory(storage_path('logs'), 'storage/logs/');
                }
            }

            $this->info('✅ Cache cleared successfully without database!');
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Helper untuk membersihkan direktori dengan aman
     */
    private function clearDirectory($path, $label)
    {
        if (!File::isDirectory($path)) {
            $this->warn("Directory not found: {$label}");
            return;
        }

        if (!is_writable($path)) {
            $this->warn("Cannot write to directory: {$label} (permission denied)");
            return;
        }

        File::cleanDirectory($path);
        File::ensureDirectoryExists($path); // pastikan direktori tetap ada
        $this->info("Cleared: {$label}");
    }
}
