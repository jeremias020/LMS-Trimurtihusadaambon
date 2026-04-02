<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\ModernUserController;

class TestControllerMethodCommand extends Command
{
    protected $signature = 'test:controller-method';
    protected $description = 'Test controller methods for guru and siswa pages';

    public function handle()
    {
        $this->info('=== TESTING CONTROLLER METHODS ===');
        
        try {
            // Test controller instantiation
            $this->info("\n🎯 Testing Controller Instantiation:");
            $controller = new ModernUserController();
            $this->line("  ✅ ModernUserController created successfully");
            
            // Test guruIndex method
            $this->info("\n👨‍🏫 Testing guruIndex Method:");
            try {
                $guruView = $controller->guruIndex();
                $this->line("  ✅ guruIndex method executed");
                $this->line("  📄 View: " . get_class($guruView));
                $this->line("  📁 View name: " . $guruView->name());
                $this->line("  📦 Data keys: " . implode(', ', array_keys($guruView->getData())));
            } catch (\Exception $e) {
                $this->line("  ❌ Error in guruIndex: " . $e->getMessage());
                $this->line("  📁 File: " . $e->getFile());
                $this->line("  📍 Line: " . $e->getLine());
            }
            
            // Test siswaIndex method
            $this->info("\n👨‍🎓 Testing siswaIndex Method:");
            try {
                $siswaView = $controller->siswaIndex();
                $this->line("  ✅ siswaIndex method executed");
                $this->line("  📄 View: " . get_class($siswaView));
                $this->line("  📁 View name: " . $siswaView->name());
                $this->line("  📦 Data keys: " . implode(', ', array_keys($siswaView->getData())));
            } catch (\Exception $e) {
                $this->line("  ❌ Error in siswaIndex: " . $e->getMessage());
                $this->line("  📁 File: " . $e->getFile());
                $this->line("  📍 Line: " . $e->getLine());
            }
            
            // Test index method
            $this->info("\n📊 Testing index Method:");
            try {
                $indexView = $controller->index();
                $this->line("  ✅ index method executed");
                $this->line("  📄 View: " . get_class($indexView));
                $this->line("  📁 View name: " . $indexView->name());
                $this->line("  📦 Data keys: " . implode(', ', array_keys($indexView->getData())));
            } catch (\Exception $e) {
                $this->line("  ❌ Error in index: " . $e->getMessage());
                $this->line("  📁 File: " . $e->getFile());
                $this->line("  📍 Line: " . $e->getLine());
            }
            
            // Check view files exist
            $this->info("\n📁 Checking View Files:");
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
            
            // Check data availability
            $this->info("\n📊 Checking Data Availability:");
            try {
                $gurus = \App\Models\UserCentral::where('role', 'guru')->get();
                $this->line("  ✅ Guru data: {$gurus->count()} records");
                
                $siswas = \App\Models\UserCentral::where('role', 'siswa')->get();
                $this->line("  ✅ Siswa data: {$siswas->count()} records");
                
                $admins = \App\Models\UserCentral::where('role', 'admin')->get();
                $this->line("  ✅ Admin data: {$admins->count()} records");
            } catch (\Exception $e) {
                $this->line("  ❌ Data error: " . $e->getMessage());
            }
            
            $this->info("\n🚀 Troubleshooting Steps:");
            $this->line("  1. Make sure Laravel server is running:");
            $this->line("     php artisan serve --host=127.0.0.1 --port=8000");
            $this->line("  2. Check authentication:");
            $this->line("     - Login as admin at http://127.0.0.1:8000/login");
            $this->line("     - Use: admin@lms-trimurti.sch.id / password");
            $this->line("  3. Clear all caches:");
            $this->line("     php artisan optimize:clear");
            $this->line("  4. Test URLs directly:");
            $this->line("     http://127.0.0.1:8000/admin/users/guru");
            $this->line("     http://127.0.0.1:8000/admin/users/siswa");
            $this->line("  5. Check browser console for errors");
            
        } catch (\Exception $e) {
            $this->error('❌ Fatal Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
