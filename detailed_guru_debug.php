<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DETAILED GURU CREATION DEBUG\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Test Direct Guru Creation with Correct Fields\n";
    echo "-------------------------------------\n";
    
    // Get latest user
    $latestUser = \App\Models\UserCentral::latest()->first();
    echo "Latest user: {$latestUser->name} (ID: {$latestUser->id})\n";
    
    // Test creating guru profile with correct field names
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
    
    echo "Attempting to create guru profile with correct fields...\n";
    
    try {
        $guru = \App\Models\Guru::create($guruData);
        echo "✅ Guru created successfully: {$guru->name}\n";
        echo "Guru ID: {$guru->id}\n";
    } catch (\Exception $e) {
        echo "❌ Guru creation FAILED: " . $e->getMessage() . "\n";
        echo "Error code: " . $e->getCode() . "\n";
        
        // Check if it's a constraint issue
        if (str_contains($e->getMessage(), 'Integrity constraint')) {
            echo "🔍 This is a constraint issue!\n";
            
            // Check for duplicate user_id
            $existingGuru = \App\Models\Guru::where('user_id', $latestUser->id)->first();
            if ($existingGuru) {
                echo "❌ Guru with user_id {$latestUser->id} already exists!\n";
                echo "Existing guru: {$existingGuru->name} (ID: {$existingGuru->id})\n";
            }
        }
    }
    
    echo "\nStep 2: Test Controller Method with Manual Exception Handling\n";
    echo "-------------------------------------\n";
    
    // Create new test user
    $testUser = \App\Models\UserCentral::create([
        'name' => 'Test Guru Manual ' . time(),
        'email' => 'manual' . time() . '@example.com',
        'username' => 'manual' . time(),
        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        'role' => 'guru',
        'phone' => '08123456789',
        'is_active' => true,
    ]);
    
    echo "Created test user: {$testUser->name} (ID: {$testUser->id})\n";
    
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
    
    echo "Calling controller method manually...\n";
    
    try {
        $controller = new \App\Http\Controllers\Admin\ModernUserController();
        
        // Manually execute the controller logic
        \Illuminate\Support\Facades\DB::beginTransaction();
        
        // Create user (already done above)
        $user = $testUser;
        
        // Create guru profile
        $guru = \App\Models\Guru::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'address' => $request->alamat,
            'phone' => $request->phone,
            'email_pribadi' => $request->email_pribadi,
            'mata_pelajaran' => $request->subject_id ? \App\Models\Subject::find($request->subject_id)->name : null,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'jurusan_pendidikan' => $request->jurusan_pendidikan,
            'tahun_mulai_kerja' => $request->tahun_mulai_kerja,
            'status' => 'aktif',
        ]);
        
        echo "✅ Manual guru creation successful: {$guru->name}\n";
        
        \Illuminate\Support\Facades\DB::commit();
        
        // Test redirect
        $redirect = new \Illuminate\Http\RedirectResponse(route('admin.users.guru'));
        $redirect->with('success', 'Guru berhasil ditambahkan');
        
        echo "✅ Redirect target: " . $redirect->getTargetUrl() . "\n";
        
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\DB::rollback();
        echo "❌ Manual controller execution FAILED: " . $e->getMessage() . "\n";
        echo "Error details: " . $e->getTraceAsString() . "\n";
    }
    
    echo "\nStep 3: Check Laravel Logs\n";
    echo "-------------------------------------\n";
    
    $logPath = __DIR__ . '/storage/logs/laravel.log';
    if (file_exists($logPath)) {
        $logContent = file_get_contents($logPath);
        $recentLogs = substr($logContent, -2000); // Last 2000 characters
        
        if (str_contains($recentLogs, 'Error creating guru')) {
            echo "Found recent error logs:\n";
            echo "-------------------------------------\n";
            
            // Extract recent error messages
            $lines = explode("\n", $recentLogs);
            foreach ($lines as $line) {
                if (str_contains($line, 'Error creating guru') || str_contains($line, 'ERROR')) {
                    echo $line . "\n";
                }
            }
        } else {
            echo "No recent guru creation errors found in logs\n";
        }
    } else {
        echo "Log file not found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Fatal error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
