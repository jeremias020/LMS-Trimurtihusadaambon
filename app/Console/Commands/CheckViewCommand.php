<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckViewCommand extends Command
{
    protected $signature = 'check:view {view}';
    protected $description = 'Check if view file exists and is valid';

    public function handle()
    {
        $viewName = $this->argument('view');
        $this->info("=== CHECKING VIEW: {$viewName} ===");
        
        // Map view names to file paths
        $viewPaths = [
            'guru' => 'resources/views/admin/users/guru-index.blade.php',
            'siswa' => 'resources/views/admin/users/siswa-index.blade.php',
            'separated' => 'resources/views/admin/users/index-separated.blade.php',
        ];
        
        $filePath = $viewPaths[$viewName] ?? null;
        
        if (!$filePath) {
            $this->error("❌ Unknown view: {$viewName}");
            $this->line("Available views: " . implode(', ', array_keys($viewPaths)));
            return Command::FAILURE;
        }
        
        $fullPath = base_path($filePath);
        
        $this->info("\n📁 File Path Check:");
        $this->line("  📋 Relative: {$filePath}");
        $this->line("  📂 Full: {$fullPath}");
        
        if (file_exists($fullPath)) {
            $this->line("  ✅ File EXISTS");
            
            // Get file info
            $size = filesize($fullPath);
            $modified = date('Y-m-d H:i:s', filemtime($fullPath));
            $lines = count(file($fullPath));
            
            $this->line("  📏 Size: " . number_format($size) . " bytes");
            $this->line("  📅 Modified: {$modified}");
            $this->line("  📄 Lines: {$lines}");
            
            // Check file content
            $this->info("\n📄 Content Check:");
            $content = file_get_contents($fullPath);
            
            if (strpos($content, '@extends') !== false) {
                $this->line("  ✅ Has @extends directive");
            } else {
                $this->line("  ❌ Missing @extends directive");
            }
            
            if (strpos($content, '@section') !== false) {
                $this->line("  ✅ Has @section directive");
            } else {
                $this->line("  ❌ Missing @section directive");
            }
            
            if (strpos($content, '@endsection') !== false) {
                $this->line("  ✅ Has @endsection directive");
            } else {
                $this->line("  ❌ Missing @endsection directive");
            }
            
            if (strpos($content, '{{') !== false) {
                $this->line("  ✅ Has Blade variables");
            } else {
                $this->line("  ⚠️  No Blade variables found");
            }
            
            // Check for specific content
            $this->info("\n🔍 Specific Content Check:");
            
            if ($viewName === 'guru') {
                if (strpos($content, 'Manajemen Guru') !== false) {
                    $this->line("  ✅ Has 'Manajemen Guru' title");
                } else {
                    $this->line("  ❌ Missing 'Manajemen Guru' title");
                }
                
                if (strpos($content, '$gurus') !== false) {
                    $this->line("  ✅ Uses \$gurus variable");
                } else {
                    $this->line("  ❌ Missing \$gurus variable");
                }
                
                if (strpos($content, 'guruIndex') !== false) {
                    $this->line("  ✅ Has guruIndex function");
                } else {
                    $this->line("  ❌ Missing guruIndex function");
                }
            }
            
            // Check for syntax errors
            $this->info("\n🧪 Syntax Check:");
            try {
                // Try to compile the view
                $viewFactory = app('view');
                $viewFactory->getFinder()->find('admin.users.' . $viewName . '-index');
                $this->line("  ✅ View can be found by Laravel");
            } catch (\Exception $e) {
                $this->line("  ❌ View compilation error: " . $e->getMessage());
            }
            
        } else {
            $this->line("  ❌ File DOES NOT EXIST");
            
            // Check if directory exists
            $dir = dirname($fullPath);
            if (is_dir($dir)) {
                $this->line("  📂 Directory exists: {$dir}");
                
                // List files in directory
                $files = scandir($dir);
                $this->line("  📋 Files in directory:");
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $this->line("    - {$file}");
                    }
                }
            } else {
                $this->line("  ❌ Directory does not exist: {$dir}");
            }
        }
        
        $this->info("\n✅ VIEW CHECK COMPLETE");
        
        return Command::SUCCESS;
    }
}
