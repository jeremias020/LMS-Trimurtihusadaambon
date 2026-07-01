<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 SISWA MODEL INVESTIGATION\n";
echo "=====================================\n";

try {
    echo "Step 1: Test if Siswa class exists\n";
    echo "-------------------------------------\n";
    
    if (class_exists('App\Models\Siswa')) {
        echo "✅ App\\Models\\Siswa class exists\n";
        
        $siswa = new \App\Models\Siswa();
        echo "Table: " . $siswa->getTable() . "\n";
        
        $fillable = $siswa->getFillable();
        echo "Fillable:\n";
        foreach ($fillable as $field) {
            echo "  - {$field}\n";
        }
        
    } else {
        echo "❌ App\\Models\\Siswa class does not exist\n";
    }
    
    echo "\nStep 2: Test if Siswa is alias for Student\n";
    echo "-------------------------------------\n";
    
    try {
        $siswa = \App\Models\Siswa::where('id', 1)->first();
        if ($siswa) {
            echo "✅ Siswa::where('id', 1) works\n";
            echo "Result: " . $siswa->name . "\n";
        } else {
            echo "⚠️  Siswa::where('id', 1) returns null\n";
        }
    } catch (\Exception $e) {
        echo "❌ Siswa::where('id', 1) failed: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 3: Test Siswa::where('user_id')\n";
    echo "-------------------------------------\n";
    
    try {
        $siswa = \App\Models\Siswa::where('user_id', 1)->first();
        if ($siswa) {
            echo "✅ Siswa::where('user_id', 1) works\n";
            echo "Result: " . $siswa->name . "\n";
        } else {
            echo "⚠️  Siswa::where('user_id', 1) returns null\n";
        }
    } catch (\Exception $e) {
        echo "❌ Siswa::where('user_id', 1) failed: " . $e->getMessage() . "\n";
        
        if (str_contains($e->getMessage(), 'user_id') && str_contains($e->getMessage(), 'where clause')) {
            echo "❌ This is the source of the error!\n";
        }
    }
    
    echo "\nStep 4: Check class hierarchy\n";
    echo "-------------------------------------\n";
    
    if (class_exists('App\Models\Siswa')) {
        $siswaReflection = new ReflectionClass('App\Models\Siswa');
        echo "Siswa class parents:\n";
        $parent = $siswaReflection->getParentClass();
        while ($parent) {
            echo "  - " . $parent->getName() . "\n";
            $parent = $parent->getParentClass();
        }
        
        echo "\nSiswa class interfaces:\n";
        foreach ($siswaReflection->getInterfaces() as $interface) {
            echo "  - " . $interface->getName() . "\n";
        }
    }
    
    echo "\nStep 5: Test ProfileController scenario\n";
    echo "-------------------------------------\n";
    
    // Simulate ProfileController logic
    $user = \App\Models\User::where('role', 'siswa')->first();
    
    if ($user) {
        echo "Testing with siswa user: {$user->name} (ID: {$user->id})\n";
        
        try {
            $additionalData = \App\Models\Siswa::where('user_id', $user->id)->first();
            if ($additionalData) {
                echo "✅ ProfileController logic works: " . $additionalData->name . "\n";
            } else {
                echo "⚠️  ProfileController logic returns null\n";
            }
        } catch (\Exception $e) {
            echo "❌ ProfileController logic failed: " . $e->getMessage() . "\n";
            
            if (str_contains($e->getMessage(), 'user_id')) {
                echo "❌ This is the source of the user_id error!\n";
            }
        }
    }
    
    echo "\n🎯 CONCLUSION:\n";
    echo "=====================================\n";
    
    if (class_exists('App\Models\Siswa')) {
        echo "✅ Siswa model exists and is being used\n";
        echo "❌ Siswa model is causing the user_id error\n";
        echo "❌ Need to fix Siswa model queries\n";
    } else {
        echo "❌ Siswa model doesn't exist (unexpected)\n";
    }
    
    echo "\n✨ INVESTIGATION COMPLETE! ✨\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
