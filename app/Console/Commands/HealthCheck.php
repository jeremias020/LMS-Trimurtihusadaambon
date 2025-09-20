<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Notification;
use App\Models\Material;
use App\Models\Assignment;

class HealthCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:health';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comprehensive health check for LMS Trimurti';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏥 LMS Trimurti Health Check');
        $this->newLine();
        
        // Database Check
        $this->checkDatabase();
        
        // PHP Extensions Check
        $this->checkPhpExtensions();
        
        // Storage Check
        $this->checkStorage();
        
        // Data Check
        $this->checkData();
        
        // Performance Check
        $this->checkPerformance();
        
        // Security Check
        $this->checkSecurity();
        
        $this->newLine();
        $this->info('✅ Health check completed!');
    }
    
    private function checkDatabase()
    {
        $this->line('🔍 Database Connection...');
        try {
            DB::connection()->getPdo();
            $this->info('  ✅ Database connected');
            
            // Check critical tables
            $tables = ['users', 'notifications', 'materials', 'assignments', 'practicals'];
            foreach ($tables as $table) {
                $count = DB::table($table)->count();
                $this->line("  📊 {$table}: {$count} records");
            }
        } catch (\Exception $e) {
            $this->error('  ❌ Database connection failed: ' . $e->getMessage());
        }
    }
    
    private function checkPhpExtensions()
    {
        $this->line('🧩 PHP Extensions...');
        $extensions = ['gd', 'zip', 'curl', 'openssl', 'mbstring', 'json', 'pdo_mysql'];
        
        foreach ($extensions as $ext) {
            if (extension_loaded($ext)) {
                $this->info("  ✅ {$ext} - enabled");
            } else {
                $this->error("  ❌ {$ext} - missing (needed for full functionality)");
            }
        }
    }
    
    private function checkStorage()
    {
        $this->line('💾 Storage...');
        
        // Check if storage is linked
        if (is_link(public_path('storage'))) {
            $this->info('  ✅ Storage linked');
        } else {
            $this->error('  ❌ Storage not linked');
        }
        
        // Check writable directories
        $directories = ['storage/logs', 'storage/app', 'storage/framework', 'bootstrap/cache'];
        foreach ($directories as $dir) {
            if (is_writable(base_path($dir))) {
                $this->info("  ✅ {$dir} - writable");
            } else {
                $this->error("  ❌ {$dir} - not writable");
            }
        }
    }
    
    private function checkData()
    {
        $this->line('📊 Data Status...');
        
        $stats = [
            'Users' => User::count(),
            'Admin' => User::where('role', 'admin')->count(),
            'Guru' => User::where('role', 'guru')->count(),
            'Siswa' => User::where('role', 'siswa')->count(),
            'Materials' => Material::count(),
            'Assignments' => Assignment::count(),
            'Notifications' => Notification::count(),
        ];
        
        foreach ($stats as $label => $count) {
            $this->line("  📈 {$label}: {$count}");
        }
    }
    
    private function checkPerformance()
    {
        $this->line('⚡ Performance...');
        
        // Check if config is cached
        if (file_exists(base_path('bootstrap/cache/config.php'))) {
            $this->info('  ✅ Config cached');
        } else {
            $this->line('  ⚠️  Config not cached (run: php artisan config:cache)');
        }
        
        // Check if routes are cached
        if (file_exists(base_path('bootstrap/cache/routes-v7.php'))) {
            $this->info('  ✅ Routes cached');
        } else {
            $this->line('  ⚠️  Routes not cached (run: php artisan route:cache)');
        }
        
        // Memory usage
        $memory = round(memory_get_usage(true) / 1024 / 1024, 2);
        $this->line("  🧠 Memory usage: {$memory} MB");
    }
    
    private function checkSecurity()
    {
        $this->line('🔒 Security...');
        
        // Check APP_KEY
        if (config('app.key')) {
            $this->info('  ✅ APP_KEY set');
        } else {
            $this->error('  ❌ APP_KEY not set (run: php artisan key:generate)');
        }
        
        // Check DEBUG mode
        if (config('app.debug')) {
            $this->line('  ⚠️  Debug mode enabled (disable for production)');
        } else {
            $this->info('  ✅ Debug mode disabled');
        }
        
        // Check HTTPS (for production)
        if (request()->isSecure()) {
            $this->info('  ✅ HTTPS enabled');
        } else {
            $this->line('  ⚠️  HTTP only (enable HTTPS for production)');
        }
    }
}
