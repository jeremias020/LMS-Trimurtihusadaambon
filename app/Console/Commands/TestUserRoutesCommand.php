<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class TestUserRoutesCommand extends Command
{
    protected $signature = 'test:user-routes';
    protected $description = 'Test user management routes';

    public function handle()
    {
        $this->info('=== TESTING USER MANAGEMENT ROUTES ===');
        
        try {
            // Test old routes
            $this->testOldRoutes();
            
            // Test new separated routes
            $this->testNewRoutes();
            
            // Show route summary
            $this->showRouteSummary();
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
    
    private function testOldRoutes()
    {
        $this->info("\n📋 OLD USER MANAGEMENT ROUTES:");
        
        $oldRoutes = [
            'admin.users.index' => '/admin/users',
            'admin.users.create' => '/admin/users/create',
            'admin.users.store' => '/admin/users (POST)',
            'admin.users.edit' => '/admin/users/{id}/edit',
            'admin.users.update' => '/admin/users/{id} (PUT)',
            'admin.users.destroy' => '/admin/users/{id} (DELETE)',
        ];
        
        foreach ($oldRoutes as $name => $path) {
            if (Route::has($name)) {
                $this->line("  ✅ {$name}: {$path}");
            } else {
                $this->line("  ❌ {$name}: {$path} - NOT FOUND");
            }
        }
    }
    
    private function testNewRoutes()
    {
        $this->info("\n🎯 NEW SEPARATED USER ROUTES:");
        
        $newRoutes = [
            'admin.users.separated' => '/admin/users/separated',
            'admin.users.guru' => '/admin/users/guru',
            'admin.users.siswa' => '/admin/users/siswa',
            'admin.users.create.admin' => '/admin/users/create/admin',
            'admin.users.create.guru' => '/admin/users/create/guru',
            'admin.users.create.siswa' => '/admin/users/create/siswa',
            'admin.users.store.admin' => '/admin/users/store/admin (POST)',
            'admin.users.store.guru' => '/admin/users/store/guru (POST)',
            'admin.users.store.siswa' => '/admin/users/store/siswa (POST)',
        ];
        
        foreach ($newRoutes as $name => $path) {
            if (Route::has($name)) {
                $this->line("  ✅ {$name}: {$path}");
            } else {
                $this->line("  ❌ {$name}: {$path} - NOT FOUND");
            }
        }
    }
    
    private function showRouteSummary()
    {
        $this->info("\n📊 ROUTE SUMMARY:");
        $this->line("  🔄 Old System: Combined table (single table)");
        $this->line("  🎯 New System: Separated tables (3 tables) + Individual pages");
        $this->line("");
        $this->line("  📋 OLD ROUTES:");
        $this->line("    - /admin/users → Combined table view");
        $this->line("    - /admin/users/create → Single form for all roles");
        $this->line("");
        $this->line("  🎯 NEW ROUTES:");
        $this->line("    - /admin/users/separated → 3 separated tables");
        $this->line("    - /admin/users/guru → Guru-only table");
        $this->line("    - /admin/users/siswa → Siswa-only table");
        $this->line("    - /admin/users/create/admin → Admin-specific form");
        $this->line("    - /admin/users/create/guru → Guru-specific form");
        $this->line("    - /admin/users/create/siswa → Siswa-specific form");
        $this->line("");
        $this->line("  🔗 ACCESS URLS:");
        $this->line("    Old System: http://localhost:8000/admin/users");
        $this->line("    New System: http://localhost:8000/admin/users/separated");
        $this->line("    Guru Only: http://localhost:8000/admin/users/guru");
        $this->line("    Siswa Only: http://localhost:8000/admin/users/siswa");
        $this->line("");
        $this->line("  🎨 SIDEBAR MENU STRUCTURE:");
        $this->line("    📁 Users (Dropdown)");
        $this->line("      ├── 📊 Semua Users → /admin/users/separated");
        $this->line("      ├── 👨‍🏫 Guru → /admin/users/guru");
        $this->line("      ├── 👨‍🎓 Siswa → /admin/users/siswa");
        $this->line("      └── ⚙️  Users (Lama) → /admin/users");
        $this->line("");
        $this->line("  🎨 BUTTON EXAMPLES:");
        $this->line("    <!-- Old System Button -->");
        $this->line("    <a href=\"{{ route('admin.users.index') }}\" class=\"btn btn-primary\">");
        $this->line("        <i class=\"fas fa-users\"></i> Manajemen Pengguna");
        $this->line("    </a>");
        $this->line("");
        $this->line("    <!-- New System Button -->");
        $this->line("    <a href=\"{{ route('admin.users.separated') }}\" class=\"btn btn-info\">");
        $this->line("        <i class=\"fas fa-table-columns\"></i> Tampilan Terpisah");
        $this->line("    </a>");
        $this->line("");
        $this->line("    <!-- Guru Only Button -->");
        $this->line("    <a href=\"{{ route('admin.users.guru') }}\" class=\"btn btn-success\">");
        $this->line("        <i class=\"fas fa-chalkboard-teacher\"></i> Data Guru");
        $this->line("    </a>");
        $this->line("");
        $this->line("    <!-- Siswa Only Button -->");
        $this->line("    <a href=\"{{ route('admin.users.siswa') }}\" class=\"btn btn-warning\">");
        $this->line("        <i class=\"fas fa-user-graduate\"></i> Data Siswa");
        $this->line("    </a>");
        $this->line("");
        $this->line("  🎯 CREATE BUTTONS:");
        $this->line("    <a href=\"{{ route('admin.users.create.admin') }}\" class=\"btn btn-primary\">");
        $this->line("        <i class=\"fas fa-user-shield\"></i> Tambah Admin");
        $this->line("    </a>");
        $this->line("    <a href=\"{{ route('admin.users.create.guru') }}\" class=\"btn btn-success\">");
        $this->line("        <i class=\"fas fa-chalkboard-teacher\"></i> Tambah Guru");
        $this->line("    </a>");
        $this->line("    <a href=\"{{ route('admin.users.create.siswa') }}\" class=\"btn btn-warning\">");
        $this->line("        <i class=\"fas fa-user-graduate\"></i> Tambah Siswa");
        $this->line("    </a>");
    }
}
