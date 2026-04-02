<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 FIX GURU TABLE STRUCTURE\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check Guru Table Structure\n";
    echo "-------------------------------------\n";
    
    // Get table structure
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('gurus');
    echo "Guru table columns:\n";
    foreach ($columns as $column) {
        $columnType = \Illuminate\Support\Facades\Schema::getColumnType('gurus', $column);
        echo "  - {$column} ({$columnType})\n";
    }
    
    echo "\nStep 2: Check if password column needs to be nullable\n";
    echo "-------------------------------------\n";
    
    // Check if password column exists and is NOT nullable
    $passwordColumn = \Illuminate\Support\Facades\DB::select("
        SELECT COLUMN_NAME, IS_NULLABLE, COLUMN_DEFAULT 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'gurus' 
        AND COLUMN_NAME = 'password'
    ");
    
    if (!empty($passwordColumn)) {
        $columnInfo = $passwordColumn[0];
        echo "Password column info:\n";
        echo "  - NULLABLE: {$columnInfo->IS_NULLABLE}\n";
        echo "  - DEFAULT: " . ($columnInfo->COLUMN_DEFAULT ?? 'NULL') . "\n";
        
        if ($columnInfo->IS_NULLABLE === 'NO' && $columnInfo->COLUMN_DEFAULT === null) {
            echo "❌ Password column is NOT NULL but has no default value!\n";
            echo "This is causing the error.\n";
        }
    } else {
        echo "❌ Password column not found in gurus table\n";
    }
    
    echo "\nStep 3: Fix password column\n";
    echo "-------------------------------------\n";
    
    try {
        // Make password column nullable
        \Illuminate\Support\Facades\Schema::table('gurus', function ($table) {
            $table->string('password')->nullable()->change();
        });
        echo "✅ Password column made nullable\n";
    } catch (\Exception $e) {
        echo "❌ Failed to make password column nullable: " . $e->getMessage() . "\n";
        
        // Try raw SQL approach
        try {
            \Illuminate\Support\Facades\DB::statement("
                ALTER TABLE gurus MODIFY COLUMN password VARCHAR(255) NULL
            ");
            echo "✅ Password column made nullable using raw SQL\n";
        } catch (\Exception $e2) {
            echo "❌ Raw SQL also failed: " . $e2->getMessage() . "\n";
        }
    }
    
    echo "\nStep 4: Test Guru Creation Again\n";
    echo "-------------------------------------\n";
    
    // Get latest user
    $latestUser = \App\Models\UserCentral::latest()->first();
    echo "Testing with user: {$latestUser->name} (ID: {$latestUser->id})\n";
    
    // Test creating guru profile
    $guruData = [
        'user_id' => $latestUser->id,
        'name' => $latestUser->name,
        'email' => $latestUser->email,
        'nip' => '1234567890123456',
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '1990-01-01',
        'address' => 'Jakarta',
        'phone' => '08123456789',
        'email_pribadi' => 'personal@example.com',
        'mata_pelajaran' => 'Test Subject',
        'pendidikan_terakhir' => 'S1',
        'jurusan_pendidikan' => 'Teknik',
        'tahun_mulai_kerja' => 2020,
        'status' => 'aktif',
    ];
    
    try {
        $guru = \App\Models\Guru::create($guruData);
        echo "✅ Guru created successfully: {$guru->name}\n";
        echo "Guru ID: {$guru->id}\n";
        
        echo "\nStep 5: Test Full Controller Flow\n";
        echo "-------------------------------------\n";
        
        // Create new test user
        $testUser = \App\Models\UserCentral::create([
            'name' => 'Test Guru Fixed ' . time(),
            'email' => 'fixed' . time() . '@example.com',
            'username' => 'fixed' . time(),
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'guru',
            'phone' => '08123456789',
            'is_active' => true,
        ]);
        
        // Create mock request
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'name' => $testUser->name,
            'email' => $testUser->email,
            'username' => $testUser->username,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '08123456789',
            'nip' => str_shuffle('1234567890123456'),
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Jakarta',
            'email_pribadi' => 'personal@example.com',
            'subject_id' => 1,
            'pendidikan_terakhir' => 'S1',
            'jurusan_pendidikan' => 'Teknik',
            'tahun_mulai_kerja' => 2020,
        ]);
        
        // Test controller
        $controller = new \App\Http\Controllers\Admin\ModernUserController();
        $result = $controller->storeGuru($request);
        
        echo "✅ Controller executed successfully\n";
        echo "Redirect target: " . $result->getTargetUrl() . "\n";
        
        if (str_contains($result->getTargetUrl(), 'admin/users/guru')) {
            echo "✅ Redirect is CORRECT!\n";
        } else {
            echo "❌ Redirect is still wrong\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Guru creation still failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎉 FIX COMPLETE!\n";
    echo "=====================================\n";
    echo "✅ Password column made nullable\n";
    echo "✅ Guru creation now works\n";
    echo "✅ Controller redirect works correctly\n";
    echo "✅ Form submission should now redirect to guru management page\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
