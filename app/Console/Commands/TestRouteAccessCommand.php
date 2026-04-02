<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class TestRouteAccessCommand extends Command
{
    protected $signature = 'test:route-access';
    protected $description = 'Test direct route access';

    public function handle()
    {
        $this->info('=== TESTING ROUTE ACCESS ===');
        
        try {
            // Test if routes are registered
            $this->testRouteRegistration();
            
            // Test controller methods
            $this->testControllerMethods();
            
            // Test view files
            $this->testViewFiles();
            
            // Show access URLs
            $this->showAccessUrls();
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
    
    private function testRouteRegistration()
    {
        $this->info("\n📋 ROUTE REGISTRATION:");
        
        $routes = [
            'admin.users.guru' => '/admin/users/guru',
            'admin.users.siswa' => '/admin/users/siswa',
            'admin.users.separated' => '/admin/users/separated',
        ];
        
        foreach ($routes as $name => $path) {
            if (Route::has($name)) {
                $route = Route::getRoutes()->getByName($name);
                $this->line("  ✅ {$name}: {$path}");
                $this->line("     Controller: " . $route->getActionName());
            } else {
                $this->line("  ❌ {$name}: {$path} - NOT FOUND");
            }
        }
    }
    
    private function testControllerMethods()
    {
        $this->info("\n🎯 CONTROLLER METHODS:");
        
        try {
            $controller = new \App\Http\Controllers\Admin\ModernUserController();
            
            // Test guruIndex method
            if (method_exists($controller, 'guruIndex')) {
                $this->line("  ✅ guruIndex method exists");
            } else {
                $this->line("  ❌ guruIndex method NOT found");
            }
            
            // Test siswaIndex method
            if (method_exists($controller, 'siswaIndex')) {
                $this->line("  ✅ siswaIndex method exists");
            } else {
                $this->line("  ❌ siswaIndex method NOT found");
            }
            
            // Test index method
            if (method_exists($controller, 'index')) {
                $this->line("  ✅ index method exists");
            } else {
                $this->line("  ❌ index method NOT found");
            }
            
        } catch (\Exception $e) {
            $this->line("  ❌ Controller error: " . $e->getMessage());
        }
    }
    
    private function testViewFiles()
    {
        $this->info("\n📁 VIEW FILES:");
        
        $views = [
            'admin.users.guru-index' => 'resources/views/admin/users/guru-index.blade.php',
            'admin.users.siswa-index' => 'resources/views/admin/users/siswa-index.blade.php',
            'admin.users.index-separated' => 'resources/views/admin/users/index-separated.blade.php',
        ];
        
        foreach ($views as $name => $path) {
            if (file_exists(base_path($path))) {
                $this->line("  ✅ {$name}: {$path}");
            } else {
                $this->line("  ❌ {$name}: {$path} - NOT FOUND");
            }
        }
    }
    
    private function showAccessUrls()
    {
        $this->info("\n🔗 ACCESS URLS:");
        $this->line("  🌐 Guru Page: http://127.0.0.1:8000/admin/users/guru");
        $this->line("  🌐 Siswa Page: http://127.0.0.1:8000/admin/users/siswa");
        $this->line("  🌐 Separated Page: http://127.0.0.1:8000/admin/users/separated");
        $this->line("  🌐 Old System: http://127.0.0.1:8000/admin/users");
        $this->line("");
        $this->line("  🔧 TROUBLESHOOTING:");
        $this->line("  1. Make sure Laravel server is running:");
        $this->line("     php artisan serve --host=127.0.0.1 --port=8000");
        $this->line("  2. Clear all caches:");
        $this->line("     php artisan cache:clear");
        $this->line("     php artisan config:clear");
        $this->line("     php artisan route:clear");
        $this->line("  3. Check .env file:");
        $this->line("     APP_URL=http://127.0.0.1:8000");
        $this->line("  4. Check middleware:");
        $this->line("     Make sure 'auth' and 'admin' middleware are working");
        $this->line("  5. Check authentication:");
        $this->line("     Make sure you're logged in as admin");
    }
}
