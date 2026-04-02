<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeepDebugCommand extends Command
{
    protected $signature = 'debug:deep';
    protected $description = 'Deep debugging for 404 issues';

    public function handle()
    {
        $this->info('=== DEEP DEBUGGING FOR 404 ===');
        
        // 1. Check Laravel version and compatibility
        $this->info("\n🔍 Laravel Environment:");
        $this->line("  📋 Version: " . app()->version());
        $this->line("  📁 Environment: " . app()->environment());
        $this->line("  🌐 URL: " . config('app.url'));
        $this->line("  📂 Base path: " . base_path());
        
        // 2. Check route registration in detail
        $this->info("\n🛣️  Detailed Route Analysis:");
        $routes = app('router')->getRoutes();
        $guruRoute = null;
        
        foreach ($routes as $route) {
            if ($route->getName() === 'admin.users.guru') {
                $guruRoute = $route;
                break;
            }
        }
        
        if ($guruRoute) {
            $this->line("  ✅ Route found: " . $guruRoute->uri());
            $this->line("  🎯 Action: " . $guruRoute->getActionName());
            $this->line("  🔐 Middleware: " . json_encode($guruRoute->middleware()));
            $this->line("  📋 Methods: " . json_encode($guruRoute->methods()));
            $this->line("  📦 Action array: " . json_encode($guruRoute->getAction()));
        } else {
            $this->line("  ❌ Route NOT found");
            
            // List all admin routes
            $this->line("  📋 All admin routes:");
            foreach ($routes as $route) {
                if (strpos($route->uri(), 'admin/users') !== false) {
                    $this->line("    - " . $route->getName() . " → " . $route->uri());
                }
            }
        }
        
        // 3. Check controller class resolution
        $this->info("\n🎮 Controller Resolution:");
        try {
            $controllerClass = 'App\Http\Controllers\Admin\ModernUserController';
            
            if (class_exists($controllerClass)) {
                $this->line("  ✅ Class exists: " . $controllerClass);
                
                $reflection = new \ReflectionClass($controllerClass);
                $this->line("  📁 File: " . $reflection->getFileName());
                $this->line("  📅 Modified: " . date('Y-m-d H:i:s', filemtime($reflection->getFileName())));
                
                // Check method
                if ($reflection->hasMethod('guruIndex')) {
                    $method = $reflection->getMethod('guruIndex');
                    $this->line("  ✅ Method exists: guruIndex");
                    $this->line("  📄 Parameters: " . $method->getNumberOfParameters());
                    $this->line("  🔄 Return type: " . $method->getReturnType()?->getName() ?? 'void');
                } else {
                    $this->line("  ❌ Method guruIndex NOT found");
                }
            } else {
                $this->line("  ❌ Class NOT found: " . $controllerClass);
            }
        } catch (\Exception $e) {
            $this->line("  ❌ Controller error: " . $e->getMessage());
        }
        
        // 4. Check view resolution
        $this->info("\n📁 View Resolution:");
        try {
            $viewFactory = app('view');
            $finder = $viewFactory->getFinder();
            
            $viewName = 'admin.users.guru-index';
            $this->line("  🔍 Looking for view: " . $viewName);
            
            $viewPath = $finder->find($viewName);
            $this->line("  ✅ View found: " . $viewPath);
            
            // Check if file actually exists
            if (file_exists($viewPath)) {
                $this->line("  ✅ File exists: " . $viewPath);
                $this->line("  📏 Size: " . filesize($viewPath) . " bytes");
            } else {
                $this->line("  ❌ File NOT exists: " . $viewPath);
            }
        } catch (\Exception $e) {
            $this->line("  ❌ View resolution error: " . $e->getMessage());
        }
        
        // 5. Check middleware stack
        $this->info("\n🔐 Middleware Stack:");
        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $middlewareGroups = $kernel->getMiddlewareGroups();
        
        if (isset($middlewareGroups['web'])) {
            $this->line("  🌐 Web middleware: " . implode(', ', $middlewareGroups['web']));
        }
        
        $routeMiddleware = $kernel->getRouteMiddleware();
        if (isset($routeMiddleware['auth'])) {
            $this->line("  🔓 Auth middleware: " . $routeMiddleware['auth']);
        }
        if (isset($routeMiddleware['admin'])) {
            $this->line("  👤 Admin middleware: " . $routeMiddleware['admin']);
        }
        
        // 6. Check potential conflicts
        $this->info("\n⚠️  Potential Conflicts:");
        
        // Check for conflicting routes
        $conflictingRoutes = [];
        foreach ($routes as $route) {
            if ($route->uri() === 'admin/users/guru' && $route->getName() !== 'admin.users.guru') {
                $conflictingRoutes[] = $route;
            }
        }
        
        if (!empty($conflictingRoutes)) {
            $this->line("  ❌ Conflicting routes found:");
            foreach ($conflictingRoutes as $route) {
                $this->line("    - " . $route->getName() . " → " . $route->uri());
            }
        } else {
            $this->line("  ✅ No conflicting routes");
        }
        
        // 7. Check file permissions
        $this->info("\n📂 File Permissions:");
        $filesToCheck = [
            'routes/web.php',
            'app/Http/Controllers/Admin/ModernUserController.php',
            'resources/views/admin/users/guru-index.blade.php',
        ];
        
        foreach ($filesToCheck as $file) {
            $fullPath = base_path($file);
            if (file_exists($fullPath)) {
                $perms = fileperms($fullPath);
                $readable = is_readable($fullPath);
                $this->line("  ✅ {$file}: " . substr(sprintf('%o', $perms), -4) . " (readable: " . ($readable ? 'yes' : 'no') . ")");
            } else {
                $this->line("  ❌ {$file}: NOT FOUND");
            }
        }
        
        // 8. Check cache status
        $this->info("\n💾 Cache Status:");
        $cacheDir = storage_path('framework/cache');
        if (is_dir($cacheDir)) {
            $files = glob($cacheDir . '/*');
            $this->line("  📁 Cache files: " . count($files));
            
            // Check route cache
            $routeCache = base_path('bootstrap/cache/routes-v7.php');
            if (file_exists($routeCache)) {
                $this->line("  🛣️  Route cache exists: " . date('Y-m-d H:i:s', filemtime($routeCache)));
            } else {
                $this->line("  🛣️  No route cache");
            }
        }
        
        // 9. Check server configuration
        $this->info("\n🌐 Server Configuration:");
        $this->line("  📋 Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A'));
        $this->line("  🌐 Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A'));
        $this->line("  📡 Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A'));
        $this->line("  🔄 Request Method: " . ($_SERVER['REQUEST_METHOD'] ?? 'N/A'));
        
        // 10. Generate test URL
        $this->info("\n🔗 Test URLs:");
        $baseUrl = config('app.url');
        $this->line("  🌐 Base URL: " . $baseUrl);
        $this->line("  👨‍🏫 Guru: " . $baseUrl . '/admin/users/guru');
        $this->line("  👨‍🎓 Siswa: " . $baseUrl . '/admin/users/siswa');
        $this->line("  📊 Separated: " . $baseUrl . '/admin/users/separated');
        
        $this->info("\n🚀 FINAL DIAGNOSIS:");
        $this->line("  If all checks above pass, the issue is:");
        $this->line("  1. Server not running on expected port");
        $this->line("  2. Wrong base URL in .env");
        $this->line("  3. Authentication issues");
        $this->line("  4. Browser/Proxy cache");
        $this->line("  5. Network/firewall blocking");
        
        return Command::SUCCESS;
    }
}
