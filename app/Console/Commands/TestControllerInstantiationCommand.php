<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestControllerInstantiationCommand extends Command
{
    protected $signature = 'test:controller-instance';
    protected $description = 'Test controller instantiation';

    public function handle()
    {
        $this->info('=== TESTING CONTROLLER INSTANTIATION ===');
        
        try {
            // Test ModernUserController
            $this->info("\n🎯 Testing ModernUserController:");
            $controller = new \App\Http\Controllers\Admin\ModernUserController();
            $this->line("  ✅ Controller instantiated successfully");
            
            // Test class name
            $this->line("  📄 Class: " . get_class($controller));
            
            // Test methods exist
            $methods = ['guruIndex', 'siswaIndex', 'index'];
            foreach ($methods as $method) {
                if (method_exists($controller, $method)) {
                    $this->line("  ✅ Method {$method} exists");
                } else {
                    $this->line("  ❌ Method {$method} NOT found");
                }
            }
            
            // Test method execution
            $this->info("\n🧪 Testing Method Execution:");
            try {
                $guruView = $controller->guruIndex();
                $this->line("  ✅ guruIndex executed successfully");
                $this->line("  📄 View: " . get_class($guruView));
                $this->line("  📁 View name: " . $guruView->name());
            } catch (\Exception $e) {
                $this->line("  ❌ guruIndex error: " . $e->getMessage());
                $this->line("  📁 File: " . $e->getFile());
                $this->line("  📍 Line: " . $e->getLine());
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Controller instantiation failed: " . $e->getMessage());
            $this->error("File: " . $e->getFile());
            $this->error("Line: " . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
