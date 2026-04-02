<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SimpleDebugCommand extends Command
{
    protected $signature = 'debug:simple';
    protected $description = 'Simple debug for 404 issues';

    public function handle()
    {
        $this->info('=== SIMPLE DEBUG ===');
        
        $this->info("\n✅ CHECKLIST:");
        
        // 1. Check routes
        $this->info("\n1️⃣ Routes:");
        if (\Illuminate\Support\Facades\Route::has('admin.users.guru')) {
            $this->line("   ✅ admin.users.guru route exists");
        } else {
            $this->line("   ❌ admin.users.guru route MISSING");
        }
        
        // 2. Check middleware
        $this->info("\n2️⃣ Middleware:");
        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $routeMiddleware = $kernel->getRouteMiddleware();
        if (isset($routeMiddleware['admin'])) {
            $this->line("   ✅ admin middleware registered");
        } else {
            $this->line("   ❌ admin middleware MISSING");
        }
        
        // 3. Check controller
        $this->info("\n3️⃣ Controller:");
        try {
            $controller = new \App\Http\Controllers\Admin\ModernUserController();
            $this->line("   ✅ ModernUserController exists");
        } catch (\Exception $e) {
            $this->line("   ❌ Controller error: " . $e->getMessage());
        }
        
        // 4. Check views
        $this->info("\n4️⃣ Views:");
        $guruView = base_path('resources/views/admin/users/guru-index.blade.php');
        if (file_exists($guruView)) {
            $this->line("   ✅ guru-index.blade.php exists");
        } else {
            $this->line("   ❌ guru-index.blade.php MISSING");
        }
        
        // 5. Check data
        $this->info("\n5️⃣ Data:");
        try {
            $gurus = \App\Models\UserCentral::where('role', 'guru')->count();
            $this->line("   ✅ {$gurus} guru records found");
        } catch (\Exception $e) {
            $this->line("   ❌ Data error: " . $e->getMessage());
        }
        
        $this->info("\n🚀 SOLUTIONS:");
        
        $this->info("\n🔧 Step 1: Start Server");
        $this->line("   php artisan serve --host=127.0.0.1 --port=8000");
        
        $this->info("\n🔧 Step 2: Clear Caches");
        $this->line("   php artisan optimize:clear");
        
        $this->info("\n🔧 Step 3: Login as Admin");
        $this->line("   URL: http://127.0.0.1:8000/login");
        $this->line("   Email: admin@lms-trimurti.sch.id");
        $this->line("   Password: password");
        
        $this->info("\n🔧 Step 4: Test URL");
        $this->line("   http://127.0.0.1:8000/admin/users/guru");
        
        $this->info("\n🔧 Step 5: Check Browser");
        $this->line("   - Open Developer Tools (F12)");
        $this->line("   - Check Console for errors");
        $this->line("   - Check Network tab");
        
        $this->info("\n📋 Common Issues:");
        $this->line("   1. Server not running");
        $this->line("   2. Not logged in");
        $this->line("   3. Wrong user role");
        $this->line("   4. Cache issues");
        $this->line("   5. Browser cache");
        
        $this->info("\n✅ If all checks pass above, the issue is:");
        $this->line("   - Server not running");
        $this->line("   - Not logged in properly");
        $this->line("   - Browser cache issues");
        
        return Command::SUCCESS;
    }
}
