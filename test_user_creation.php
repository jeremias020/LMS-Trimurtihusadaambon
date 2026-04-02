<?php
echo "=== TESTING USER CREATION PROCESS ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Simulate form data
    $requestData = [
        'name' => 'Test Siswa ' . time(),
        'email' => 'test' . time() . '@example.com',
        'role' => 'siswa',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'nis' => '12345',
        'kelas_id' => '1',
        'jurusan_id' => '1',
        'birth_date' => '2000-01-01',
        'address' => 'Test Address 123'
    ];
    
    echo "Simulating user creation with data:\n";
    echo "  - Name: {$requestData['name']}\n";
    echo "  - Email: {$requestData['email']}\n";
    echo "  - Role: {$requestData['role']}\n";
    echo "  - NIS: {$requestData['nis']}\n";
    echo "  - Kelas ID: {$requestData['kelas_id']}\n";
    echo "  - Jurusan ID: {$requestData['jurusan_id']}\n";
    
    // Check if jurusan exists
    $jurusan = \Illuminate\Support\Facades\DB::table('jurusan_new')->find($requestData['jurusan_id']);
    if ($jurusan) {
        echo "  - Jurusan: {$jurusan->nama} ({$jurusan->kode})\n";
    }
    
    // Check if kelas exists
    $kelas = \Illuminate\Support\Facades\DB::table('kelas')->find($requestData['kelas_id']);
    if ($kelas) {
        echo "  - Kelas: {$kelas->name}\n";
    }
    
    echo "\nTesting validation...\n";
    
    // Validation rules (same as controller)
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:admin,guru,siswa',
        'password' => 'required|min:8|confirmed',
        'nis' => 'required_if:role,siswa|string|min:3',
        'kelas_id' => 'required_if:role,siswa|exists:kelas,id',
        'jurusan_id' => 'nullable|exists:jurusan_new,id',
        'birth_date' => 'required_if:role,siswa|date',
        'address' => 'required_if:role,siswa|string|min:5',
    ];
    
    $validator = \Illuminate\Support\Facades\Validator::make($requestData, $rules);
    
    if ($validator->fails()) {
        echo "❌ Validation failed:\n";
        foreach ($validator->errors()->all() as $error) {
            echo "  - $error\n";
        }
        echo "\nThis would cause redirect back to create form with errors.\n";
    } else {
        echo "✅ Validation passed\n";
        echo "\nThis should redirect to admin.users.index with success message.\n";
        
        // Test actual creation (optional)
        echo "\nTesting actual user creation...\n";
        try {
            $user = new \App\Models\User();
            $user->name = $requestData['name'];
            $user->email = $requestData['email'];
            $user->role = $requestData['role'];
            $user->password = \Illuminate\Support\Facades\Hash::make($requestData['password']);
            $user->status = 'active';
            $user->save(); // Let Laravel handle timestamps
            
            // Add siswa specific fields
            if ($requestData['role'] === 'siswa') {
                $user->kelas_id = (int) $requestData['kelas_id'];
                $user->jurusan_id = (int) $requestData['jurusan_id'];
                $user->save();
            }
            
            echo "✅ User created successfully with ID: {$user->id}\n";
            
            // Clean up - delete test user
            $user->delete();
            echo "✅ Test user cleaned up\n";
            
        } catch (\Exception $e) {
            echo "❌ User creation failed: " . $e->getMessage() . "\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
