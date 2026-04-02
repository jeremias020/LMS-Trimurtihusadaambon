<?php
echo "=== TESTING USER CREATION VALIDATION ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Test validation rules
    echo "Testing validation rules...\n";
    
    // Test jurusan_id validation
    $testData = [
        'name' => 'Test User',
        'email' => 'test' . time() . '@example.com',
        'role' => 'siswa',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'nis' => '12345',
        'kelas_id' => '1',
        'jurusan_id' => '1',
        'birth_date' => '2000-01-01',
        'address' => 'Test Address'
    ];
    
    // Check if jurusan table exists
    echo "Checking jurusan table...\n";
    if (\Illuminate\Support\Facades\Schema::hasTable('jurusan')) {
        echo "✅ jurusan table exists\n";
        $count = \Illuminate\Support\Facades\DB::table('jurusan')->count();
        echo "📊 jurusan count: $count\n";
    } elseif (\Illuminate\Support\Facades\Schema::hasTable('jurusan_new')) {
        echo "✅ jurusan_new table exists\n";
        $count = \Illuminate\Support\Facades\DB::table('jurusan_new')->count();
        echo "📊 jurusan_new count: $count\n";
        echo "⚠️  Validation rule uses 'jurusan' table but model uses 'jurusan_new'\n";
    } else {
        echo "❌ No jurusan table found\n";
    }
    
    // Test the validation
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:admin,guru,siswa',
        'password' => 'required|min:8|confirmed',
        'nis' => 'required_if:role,siswa|string|min:3',
        'kelas_id' => 'required_if:role,siswa|exists:kelas,id',
        'jurusan_id' => 'nullable|exists:jurusan_new,id', // Fixed to use jurusan_new
        'birth_date' => 'required_if:role,siswa|date',
        'address' => 'required_if:role,siswa|string|min:5',
    ];
    
    echo "\nTesting validation with sample data...\n";
    
    $validator = \Illuminate\Support\Facades\Validator::make($testData, $rules);
    
    if ($validator->fails()) {
        echo "❌ Validation failed:\n";
        foreach ($validator->errors()->all() as $error) {
            echo "  - $error\n";
        }
    } else {
        echo "✅ Validation passed\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
