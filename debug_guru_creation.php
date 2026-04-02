<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEBUG GURU CREATION ERROR\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check Guru Table Structure\n";
    echo "-------------------------------------\n";
    
    $guruTableColumns = \Illuminate\Support\Facades\Schema::getColumnListing('gurus');
    echo "Guru table columns:\n";
    foreach ($guruTableColumns as $column) {
        echo "  - {$column}\n";
    }
    
    echo "\nStep 2: Check Required Columns\n";
    echo "-------------------------------------\n";
    
    $requiredColumns = [
        'user_id',
        'nip',
        'name',
        'email',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'email_pribadi',
        'mata_pelajaran',
        'pendidikan_terakhir',
        'jurusan_pendidikan',
        'tahun_mulai_kerja',
        'status'
    ];
    
    foreach ($requiredColumns as $column) {
        if (in_array($column, $guruTableColumns)) {
            echo "✅ {$column} - EXISTS\n";
        } else {
            echo "❌ {$column} - MISSING\n";
        }
    }
    
    echo "\nStep 3: Test Guru Creation Directly\n";
    echo "-------------------------------------\n";
    
    // Get latest user
    $latestUser = \App\Models\UserCentral::latest()->first();
    echo "Latest user: {$latestUser->name} (ID: {$latestUser->id})\n";
    
    // Test creating guru profile
    $guruData = [
        'user_id' => $latestUser->id,
        'nip' => '1234567890123456',
        'name' => $latestUser->name,
        'email' => $latestUser->email,
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '1990-01-01',
        'alamat' => 'Jakarta',
        'no_telepon' => '08123456789',
        'email_pribadi' => 'personal@example.com',
        'mata_pelajaran' => 'Test Subject',
        'pendidikan_terakhir' => 'S1',
        'jurusan_pendidikan' => 'Teknik',
        'tahun_mulai_kerja' => 2020,
        'status' => 'aktif',
    ];
    
    echo "Attempting to create guru profile...\n";
    
    try {
        $guru = \App\Models\Guru::create($guruData);
        echo "✅ Guru created successfully: {$guru->name}\n";
    } catch (\Exception $e) {
        echo "❌ Guru creation FAILED: " . $e->getMessage() . "\n";
        
        // Check if it's a column issue
        if (str_contains($e->getMessage(), 'Column not found')) {
            echo "🔍 This is a column issue!\n";
        }
        
        // Check if it's a constraint issue
        if (str_contains($e->getMessage(), 'Integrity constraint')) {
            echo "🔍 This is a constraint issue!\n";
        }
    }
    
    echo "\nStep 4: Check Controller Code\n";
    echo "-------------------------------------\n";
    
    $controllerPath = __DIR__ . '/app/Http/Controllers/Admin/ModernUserController.php';
    $controllerContent = file_get_contents($controllerPath);
    
    // Extract the Guru::create part
    if (preg_match('/Guru::create\(\[(.*?)\]\)/s', $controllerContent, $matches)) {
        echo "Guru::create code found:\n";
        echo $matches[1] . "\n";
    }
    
    echo "\nStep 5: Check for Missing Name Field\n";
    echo "-------------------------------------\n";
    
    // Check if name is being passed to Guru::create
    if (str_contains($controllerContent, "'name' => \$request->name")) {
        echo "✅ Name field is being passed to Guru::create\n";
    } else {
        echo "❌ Name field is NOT being passed to Guru::create\n";
        echo "This might be causing the issue!\n";
    }
    
    echo "\n🔧 SOLUTION:\n";
    echo "=====================================\n";
    
    if (!str_contains($controllerContent, "'name' => \$request->name")) {
        echo "The issue is that 'name' field is missing from Guru::create\n";
        echo "Let me fix this...\n";
        
        // Fix the controller
        $oldContent = file_get_contents($controllerPath);
        $newContent = str_replace(
            "Guru::create([\n                'user_id' => \$user->id,\n                'nip' => \$request->nip,",
            "Guru::create([\n                'user_id' => \$user->id,\n                'name' => \$request->name,\n                'email' => \$request->email,\n                'nip' => \$request->nip,",
            $oldContent
        );
        
        file_put_contents($controllerPath, $newContent);
        echo "✅ Controller updated with name and email fields\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
