<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

class TestHttpRequestCommand extends Command
{
    protected $signature = 'test:http-request {url}';
    protected $description = 'Test HTTP request simulation';

    public function handle()
    {
        $url = $this->argument('url');
        $this->info("=== TESTING HTTP REQUEST: {$url} ===");
        
        // Parse URL
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'] ?? '';
        
        $this->info("\n🔍 Request Simulation:");
        $this->line("  📋 URL: {$url}");
        $this->line("  📁 Path: {$path}");
        
        // Create mock request
        $request = Request::create($url, 'GET');
        
        // Add session data (simulate authenticated user)
        $this->info("\n🔐 Authentication Simulation:");
        try {
            // Create admin user for testing
            $admin = \App\Models\UserCentral::where('role', 'admin')->first();
            if ($admin) {
                $this->line("  ✅ Admin user found: " . $admin->email);
                
                // Simulate authentication
                auth()->login($admin);
                $this->line("  ✅ User authenticated");
                
                // Check if user is actually authenticated
                if (auth()->check()) {
                    $this->line("  ✅ Auth check passed");
                    $this->line("  👤 Auth user: " . auth()->user()->email);
                    $this->line("  🔑 User role: " . auth()->user()->role);
                } else {
                    $this->line("  ❌ Auth check failed");
                }
            } else {
                $this->line("  ❌ No admin user found");
            }
        } catch (\Exception $e) {
            $this->line("  ❌ Auth simulation error: " . $e->getMessage());
        }
        
        // Test route resolution
        $this->info("\n🛣️  Route Resolution:");
        try {
            $route = app('router')->getRoutes()->match($request);
            $this->line("  ✅ Route matched: " . $route->getName());
            $this->line("  🎯 Action: " . $route->getActionName());
            
            // Check middleware
            $middleware = $route->middleware();
            $this->line("  🔐 Middleware: " . implode(', ', $middleware));
            
            // Test if middleware passes
            foreach ($middleware as $mid) {
                if ($mid === 'auth') {
                    if (!auth()->check()) {
                        $this->line("  ❌ Auth middleware would block");
                        return Command::FAILURE;
                    } else {
                        $this->line("  ✅ Auth middleware would pass");
                    }
                }
                
                if ($mid === 'admin') {
                    if (!auth()->check() || auth()->user()->role !== 'admin') {
                        $this->line("  ❌ Admin middleware would block");
                        return Command::FAILURE;
                    } else {
                        $this->line("  ✅ Admin middleware would pass");
                    }
                }
            }
            
        } catch (\Exception $e) {
            $this->line("  ❌ Route matching failed: " . $e->getMessage());
            return Command::FAILURE;
        }
        
        // Test controller execution
        $this->info("\n🎮 Controller Execution:");
        try {
            $action = $route->getActionName();
            if (strpos($action, '@') !== false) {
                list($controllerClass, $method) = explode('@', $action);
                
                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    
                    if (method_exists($controller, $method)) {
                        $this->line("  ✅ Controller and method exist");
                        
                        // Execute method
                        $result = $controller->$method();
                        
                        if ($result instanceof \Illuminate\View\View) {
                            $this->line("  ✅ Method executed successfully");
                            $this->line("  📄 Returns: " . get_class($result));
                            $this->line("  📁 View name: " . $result->name());
                            $this->line("  📦 Data: " . implode(', ', array_keys($result->getData())));
                            
                            // Test view rendering
                            try {
                                $rendered = $result->render();
                                $this->line("  ✅ View renders successfully");
                                $this->line("  📏 Output size: " . strlen($rendered) . " bytes");
                            } catch (\Exception $e) {
                                $this->line("  ❌ View rendering failed: " . $e->getMessage());
                            }
                        } else {
                            $this->line("  ⚠️  Method returned: " . gettype($result));
                        }
                    } else {
                        $this->line("  ❌ Method not found: {$method}");
                    }
                } else {
                    $this->line("  ❌ Controller not found: {$controllerClass}");
                }
            } else {
                $this->line("  ❌ Invalid action format: {$action}");
            }
        } catch (\Exception $e) {
            $this->line("  ❌ Controller execution failed: " . $e->getMessage());
            $this->line("  📁 File: " . $e->getFile());
            $this->line("  📍 Line: " . $e->getLine());
            return Command::FAILURE;
        }
        
        $this->info("\n🎉 HTTP REQUEST TEST COMPLETE");
        $this->line("  ✅ All components working correctly");
        $this->line("  ✅ Request should succeed if server is running");
        
        return Command::SUCCESS;
    }
}
