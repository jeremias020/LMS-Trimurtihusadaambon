<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class DebugAuthCommand extends Command
{
    protected $signature = 'debug:auth';
    protected $description = 'Debug authentication and middleware issues';

    public function handle()
    {
        $this->info('=== DEBUGGING AUTHENTICATION ===');
        
        $this->info("\n🔐 Authentication Status:");
        
        // Check if we're in web context
        $this->line("  📋 Context: " . app()->environment());
        
        // Check auth guard
        $this->line("  🛡️  Default guard: " . config('auth.defaults.guard'));
        
        // Check user model
        $this->line("  👤 User model: " . config('auth.providers.users.model'));
        
        // Test authentication in web context
        $this->info("\n🧪 Testing Authentication:");
        
        // Simulate web request
        $request = new \Illuminate\Http\Request();
        app()->instance('request', $request);
        
        // Start session for web context
        if (!session()->isStarted()) {
            session()->start();
        }
        
        // Check auth
        if (Auth::check()) {
            $user = Auth::user();
            $this->line("  ✅ User authenticated: " . $user->email);
            $this->line("  👤 User role: " . ($user->role ?? 'N/A'));
            $this->line("  🆔 User ID: " . $user->id);
        } else {
            $this->line("  ❌ No user authenticated");
            $this->line("  💡 This is normal in CLI context");
        }
        
        // Test admin users in database
        $this->info("\n👤 Admin Users in Database:");
        
        try {
            $adminUsers = \App\Models\UserCentral::where('role', 'admin')->get();
            foreach ($adminUsers as $admin) {
                $this->line("  ✅ " . $admin->email . " (role: " . $admin->role . ")");
            }
        } catch (\Exception $e) {
            $this->line("  ❌ Error checking admin users: " . $e->getMessage());
        }
        
        // Check middleware registration
        $this->info("\n🔧 Middleware Registration:");
        
        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $routeMiddleware = $kernel->getRouteMiddleware();
        
        if (isset($routeMiddleware['admin'])) {
            $this->line("  ✅ Admin middleware registered: " . $routeMiddleware['admin']);
        } else {
            $this->line("  ❌ Admin middleware NOT registered");
        }
        
        // Check bootstrap/app.php
        $this->info("\n📁 Bootstrap Configuration:");
        $bootstrapPath = base_path('bootstrap/app.php');
        if (file_exists($bootstrapPath)) {
            $content = file_get_contents($bootstrapPath);
            if (strpos($content, 'admin') !== false) {
                $this->line("  ✅ Admin middleware found in bootstrap/app.php");
            } else {
                $this->line("  ❌ Admin middleware NOT found in bootstrap/app.php");
            }
        }
        
        // Check routes with middleware
        $this->info("\n🛣️  Routes with Admin Middleware:");
        
        $routes = Route::getRoutes();
        $adminRoutes = [];
        
        foreach ($routes as $route) {
            if (in_array('admin', $route->middleware())) {
                $adminRoutes[] = [
                    'method' => implode('|', $route->methods()),
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                    'action' => $route->getActionName()
                ];
            }
        }
        
        if (empty($adminRoutes)) {
            $this->line("  ❌ No routes with admin middleware found");
        } else {
            foreach ($adminRoutes as $route) {
                $this->line("  ✅ {$route['method']} {$route['uri']} → {$route['action']}");
                if ($route['name']) {
                    $this->line("     Name: {$route['name']}");
                }
            }
        }
        
        // Test specific routes
        $this->info("\n🎯 Specific Route Test:");
        
        $targetRoutes = [
            'admin.users.guru',
            'admin.users.siswa',
            'admin.users.separated'
        ];
        
        foreach ($targetRoutes as $routeName) {
            if (Route::has($routeName)) {
                $route = Route::getRoutes()->getByName($routeName);
                $this->line("  ✅ {$routeName}: " . $route->uri());
                $this->line("     Middleware: " . implode(', ', $route->middleware()));
            } else {
                $this->line("  ❌ {$routeName}: NOT FOUND");
            }
        }
        
        $this->info("\n🚀 SOLUTIONS:");
        
        $this->info("\n🔧 Solution 1: Register Admin Middleware");
        $this->line("  Check bootstrap/app.php:");
        $this->line("  \$app->routeMiddleware([");
        $this->line("      'admin' => App\\Http\\Middleware\\AdminMiddleware::class,");
        $this->line("  ]);");
        
        $this->info("\n🔧 Solution 2: Clear All Caches");
        $this->line("  php artisan optimize:clear");
        $this->line("  php artisan config:cache");
        $this->line("  php artisan route:cache");
        
        $this->info("\n🔧 Solution 3: Test in Browser");
        $this->line("  1. Start server: php artisan serve --host=127.0.0.1 --port=8000");
        $this->line("  2. Login: http://127.0.0.1:8000/login");
        $this->line("  3. Try: http://127.0.0.1:8000/admin/users/guru");
        $this->line("  4. Check browser console for errors");
        
        $this->info("\n🔧 Solution 4: Check Kernel.php");
        $this->line("  Make sure admin middleware is registered in:");
        $this->line("  app/Http/Kernel.php -> \$routeMiddleware");
        
        return Command::SUCCESS;
    }
}
