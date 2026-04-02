<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

class TestUrlCommand extends Command
{
    protected $signature = 'test:url {url}';
    protected $description = 'Test specific URL and show response';

    public function handle()
    {
        $url = $this->argument('url');
        $this->info("=== TESTING URL: {$url} ===");
        
        // Parse URL to get path
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'] ?? '';
        
        $this->info("\n🔍 URL Analysis:");
        $this->line("  📋 Full URL: {$url}");
        $this->line("  📁 Path: {$path}");
        
        // Check if route exists
        $this->info("\n🛣️  Route Check:");
        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        $matchedRoute = null;
        
        foreach ($routes as $route) {
            if ($route->uri() === ltrim($path, '/')) {
                $matchedRoute = $route;
                break;
            }
        }
        
        if ($matchedRoute) {
            $this->line("  ✅ Route found: " . $matchedRoute->getName());
            $this->line("  🎯 Action: " . $matchedRoute->getActionName());
            $this->line("  🔐 Middleware: " . implode(', ', $matchedRoute->middleware()));
            $this->line("  📋 Methods: " . implode('|', $matchedRoute->methods()));
        } else {
            $this->line("  ❌ No route found for path: {$path}");
            
            // Try to find similar routes
            $this->info("\n🔍 Similar Routes:");
            foreach ($routes as $route) {
                if (strpos($route->uri(), 'users') !== false) {
                    $this->line("  📋 " . $route->getName() . " → " . $route->uri());
                }
            }
        }
        
        // Test controller method directly
        if ($matchedRoute) {
            $this->info("\n🧪 Controller Test:");
            try {
                $action = $matchedRoute->getActionName();
                if (strpos($action, '@') !== false) {
                    list($controllerClass, $method) = explode('@', $action);
                    
                    // The controllerClass should already include full namespace
                    $this->line("  🔍 Raw controller class: {$controllerClass}");
                    
                    if (class_exists($controllerClass)) {
                        $controller = new $controllerClass();
                        
                        if (method_exists($controller, $method)) {
                            $this->line("  ✅ Controller class exists: {$controllerClass}");
                            $this->line("  ✅ Method exists: {$method}");
                            
                            // Try to call the method
                            try {
                                $result = $controller->$method();
                                if ($result instanceof \Illuminate\View\View) {
                                    $this->line("  ✅ Method executed successfully");
                                    $this->line("  📄 Returns: " . get_class($result));
                                    $this->line("  📁 View: " . $result->name());
                                } else {
                                    $this->line("  ✅ Method executed, returns: " . gettype($result));
                                }
                            } catch (\Exception $e) {
                                $this->line("  ❌ Method execution failed: " . $e->getMessage());
                                $this->line("  📁 File: " . $e->getFile());
                                $this->line("  📍 Line: " . $e->getLine());
                            }
                        } else {
                            $this->line("  ❌ Method {$method} not found in controller");
                        }
                    } else {
                        $this->line("  ❌ Controller class not found: {$fullControllerClass}");
                    }
                } else {
                    $this->line("  ❌ Invalid action format: {$action}");
                }
            } catch (\Exception $e) {
                $this->line("  ❌ Controller test failed: " . $e->getMessage());
            }
        }
        
        // Check view file
        if ($matchedRoute) {
            $this->info("\n📁 View File Check:");
            $action = $matchedRoute->getActionName();
            if (strpos($action, '@') !== false) {
                list($controllerClass, $method) = explode('@', $action);
                
                // Try to determine view name based on method
                $possibleViews = [];
                
                if ($method === 'guruIndex') {
                    $possibleViews[] = 'admin.users.guru-index';
                } elseif ($method === 'siswaIndex') {
                    $possibleViews[] = 'admin.users.siswa-index';
                } elseif ($method === 'index') {
                    $possibleViews[] = 'admin.users.index-separated';
                }
                
                foreach ($possibleViews as $viewName) {
                    $viewPath = resource_path('views/' . str_replace('.', '/', $viewName) . '.blade.php');
                    if (file_exists($viewPath)) {
                        $this->line("  ✅ View exists: {$viewName}");
                        $this->line("  📁 Path: {$viewPath}");
                    } else {
                        $this->line("  ❌ View missing: {$viewName}");
                        $this->line("  📁 Expected: {$viewPath}");
                    }
                }
            }
        }
        
        $this->info("\n🚀 TROUBLESHOOTING STEPS:");
        $this->line("  1. Make sure server is running: php artisan serve --host=127.0.0.1 --port=8000");
        $this->line("  2. Clear caches: php artisan optimize:clear");
        $this->line("  3. Login as admin: http://127.0.0.1:8000/login");
        $this->line("  4. Check browser console for errors");
        $this->line("  5. Try different URL format");
        
        return Command::SUCCESS;
    }
}
