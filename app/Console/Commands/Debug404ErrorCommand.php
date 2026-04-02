<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Debug404ErrorCommand extends Command
{
    protected $signature = 'debug:404-error';
    protected $description = 'Debug 404 error for admin users pages';

    public function handle()
    {
        $this->info('=== DEBUGGING 404 ERROR ===');
        
        $this->info("\n🚨 Problem: Halaman tidak ditemukan");
        $this->info("🔍 URL: http://127.0.0.1:8000/admin/users/guru");
        
        $this->info("\n✅ Status Check:");
        
        // Check routes
        $this->info("\n📋 Routes Status:");
        $routes = [
            'admin.users.guru' => '/admin/users/guru',
            'admin.users.siswa' => '/admin/users/siswa',
            'admin.users.separated' => '/admin/users/separated',
        ];
        
        foreach ($routes as $name => $path) {
            if (\Illuminate\Support\Facades\Route::has($name)) {
                $route = \Illuminate\Support\Facades\Route::getRoutes()->getByName($name);
                $this->line("  ✅ {$name}: {$path}");
                $this->line("     → {$route->getActionName()}");
            } else {
                $this->line("  ❌ {$name}: {$path} - NOT FOUND");
            }
        }
        
        // Check middleware
        $this->info("\n🔐 Middleware Check:");
        $guruRoute = \Illuminate\Support\Facades\Route::getRoutes()->getByName('admin.users.guru');
        if ($guruRoute) {
            $middleware = $guruRoute->middleware();
            $this->line("  📋 Middleware: " . implode(', ', $middleware));
            
            if (in_array('auth', $middleware)) {
                $this->line("  🔓 Auth middleware: Required");
            }
            if (in_array('admin', $middleware)) {
                $this->line("  👤 Admin middleware: Required");
            }
        }
        
        // Check authentication
        $this->info("\n🔐 Authentication Check:");
        if (auth()->check()) {
            $user = auth()->user();
            $this->line("  ✅ User logged in: " . $user->email);
            $this->line("  👤 User role: " . ($user->role ?? 'N/A'));
        } else {
            $this->line("  ❌ No user logged in");
            $this->line("  🔑 Please login first at: http://127.0.0.1:8000/login");
        }
        
        // Check file permissions
        $this->info("\n📁 File Permissions:");
        $files = [
            'routes/web.php' => 'Route definitions',
            'app/Http/Controllers/Admin/ModernUserController.php' => 'Controller',
            'resources/views/admin/users/guru-index.blade.php' => 'Guru view',
            'resources/views/admin/users/siswa-index.blade.php' => 'Siswa view',
        ];
        
        foreach ($files as $file => $description) {
            $path = base_path($file);
            if (file_exists($path)) {
                $this->line("  ✅ {$description}: {$file}");
            } else {
                $this->line("  ❌ {$description}: {$file} - NOT FOUND");
            }
        }
        
        // Check Laravel server
        $this->info("\n🌐 Server Check:");
        $this->line("  📋 Expected server: http://127.0.0.1:8000");
        $this->line("  🔧 Command: php artisan serve --host=127.0.0.1 --port=8000");
        
        $this->info("\n🚀 SOLUTIONS:");
        
        $this->info("\n🔧 Solution 1: Start Server");
        $this->line("  php artisan serve --host=127.0.0.1 --port=8000");
        
        $this->info("\n🔧 Solution 2: Login as Admin");
        $this->line("  1. Open: http://127.0.0.1:8000/login");
        $this->line("  2. Email: admin@lms-trimurti.sch.id");
        $this->line("  3. Password: password");
        
        $this->info("\n🔧 Solution 3: Clear Caches");
        $this->line("  php artisan optimize:clear");
        $this->line("  php artisan route:clear");
        $this->line("  php artisan config:clear");
        $this->line("  php artisan view:clear");
        
        $this->info("\n🔧 Solution 4: Check Browser");
        $this->line("  1. Clear browser cache (Ctrl+F5)");
        $this->line("  2. Open developer console (F12)");
        $this->line("  3. Check for JavaScript errors");
        $this->line("  4. Check Network tab for 404 errors");
        
        $this->info("\n🔧 Solution 5: Try Different URLs");
        $this->line("  • http://127.0.0.1:8000/admin/users/guru");
        $this->line("  • http://localhost:8000/admin/users/guru");
        $this->line("  • http://127.0.0.1:8000/admin/users/siswa");
        $this->line("  • http://localhost:8000/admin/users/siswa");
        
        $this->info("\n🔧 Solution 6: Test Route Directly");
        $this->line("  php artisan route:list --name=admin.users.guru");
        $this->line("  php artisan test:controller-method");
        
        $this->info("\n📋 Common 404 Causes:");
        $this->line("  1. Server not running");
        $this->line("  2. Wrong URL (localhost vs 127.0.0.1)");
        $this->line("  3. Not logged in");
        $this->line("  4. Wrong user role (not admin)");
        $this->line("  5. Route cache issues");
        $this->line("  6. Browser cache issues");
        
        $this->info("\n✅ If all else fails:");
        $this->line("  1. Restart Laravel server");
        $this->line("  2. Clear all caches");
        $this->line("  3. Login as admin");
        $this->line("  4. Try the URL again");
        
        return Command::SUCCESS;
    }
}
