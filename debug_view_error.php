<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DETAILED VIEW ERROR DEBUG\n";
echo "=====================================\n";

try {
    // Login as siswa
    $siswaUser = \App\Models\User::where('role', 'siswa')->first();
    \Illuminate\Support\Facades\Auth::login($siswaUser);
    
    $student = \App\Models\Student::with('kelas')->where('id', $siswaUser->id)->first();
    
    echo "✅ User: {$siswaUser->name}\n";
    echo "✅ Student: " . ($student ? $student->name : 'NULL') . "\n";
    
    // Try to get the view content
    echo "\nStep 1: Test View Compilation\n";
    echo "-------------------------------------\n";
    
    $viewFactory = app('view');
    $view = $viewFactory->make('siswa.profile.edit', [
        'user' => $siswaUser,
        'student' => $student
    ]);
    
    echo "✅ View object created\n";
    echo "  View name: " . $view->getName() . "\n";
    
    // Test rendering step by step
    echo "\nStep 2: Test Rendering\n";
    echo "-------------------------------------\n";
    
    try {
        $content = $view->render();
        echo "✅ View rendered successfully\n";
        echo "  Content length: " . strlen($content) . " characters\n";
        
        // Check for error indicators in content
        if (strpos($content, 'ErrorException') !== false) {
            echo "❌ ErrorException found in content\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Rendering failed: " . $e->getMessage() . "\n";
        echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n";
        
        // Get more details
        if (strpos($e->getMessage(), 'Undefined variable') !== false) {
            echo "  This is an undefined variable error\n";
            
            // Try to find which variable
            $message = $e->getMessage();
            if (preg_match('/Undefined variable \$(\w+)/', $message, $matches)) {
                $varName = $matches[1];
                echo "  Variable name: \${$varName}\n";
                
                // Check if this is a common Laravel variable
                $commonVars = ['errors', 'auth', 'request', 'session'];
                if (in_array($varName, $commonVars)) {
                    echo "  This is a common Laravel variable\n";
                    echo "  Possible causes:\n";
                    echo "    - Missing @error directive\n";
                    echo "    - Missing middleware\n";
                    echo "    - Wrong Blade syntax\n";
                }
            }
        }
    }
    
    // Check view file syntax
    echo "\nStep 3: Check View File Syntax\n";
    echo "-------------------------------------\n";
    
    $viewPath = resource_path('views/siswa/profile/edit.blade.php');
    if (file_exists($viewPath)) {
        echo "✅ View file exists: {$viewPath}\n";
        
        $content = file_get_contents($viewPath);
        
        // Check for common syntax issues
        $issues = [];
        
        // Check for unclosed @if/@endif
        $ifCount = substr_count($content, '@if');
        $endifCount = substr_count($content, '@endif');
        if ($ifCount !== $endifCount) {
            $issues[] = "@if/@endif mismatch: {$ifCount} @if vs {$endifCount} @endif";
        }
        
        // Check for unclosed @foreach/@endforeach
        $foreachCount = substr_count($content, '@foreach');
        $endforeachCount = substr_count($content, '@endforeach');
        if ($foreachCount !== $endforeachCount) {
            $issues[] = "@foreach/@endforeach mismatch: {$foreachCount} @foreach vs {$endforeachCount} @endforeach";
        }
        
        // Check for @error/@enderror
        $errorCount = substr_count($content, '@error');
        $enderrorCount = substr_count($content, '@enderror');
        if ($errorCount !== $enderrorCount) {
            $issues[] = "@error/@enderror mismatch: {$errorCount} @error vs {$enderrorCount} @enderror";
        }
        
        if (empty($issues)) {
            echo "✅ No obvious syntax issues found\n";
        } else {
            echo "❌ Syntax issues found:\n";
            foreach ($issues as $issue) {
                echo "  - {$issue}\n";
            }
        }
        
        // Check for $errors usage
        if (strpos($content, '$errors') !== false) {
            echo "⚠️  \$errors variable found in view\n";
            
            // Find all occurrences
            $lines = explode("\n", $content);
            foreach ($lines as $lineNum => $line) {
                if (strpos($line, '$errors') !== false) {
                    echo "  Line " . ($lineNum + 1) . ": " . trim($line) . "\n";
                }
            }
        }
        
    } else {
        echo "❌ View file not found\n";
    }
    
    echo "\n🎯 RECOMMENDATIONS:\n";
    echo "=====================================\n";
    echo "1. Remove all \$errors references from view\n";
    echo "2. Use @error directive instead of \$errors variable\n";
    echo "3. Clear view cache: php artisan view:clear\n";
    echo "4. Check Laravel documentation for error handling\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
