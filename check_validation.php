<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 CHECK VALIDATION ERRORS\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Test Validation with Real Data\n";
    echo "-------------------------------------\n";
    
    // Get first subject
    $subject = \App\Models\Subject::first();
    if (!$subject) {
        echo "❌ No subjects found!\n";
        return;
    }
    
    $testData = [
        'name' => 'Test Guru Validation ' . time(),
        'email' => 'validation' . time() . '@example.com',
        'username' => 'validation' . time(),
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone' => '08123456789',
        'nip' => str_shuffle('1234567890123456'),
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '1990-01-01',
        'alamat' => 'Jakarta',
        'email_pribadi' => 'personal@example.com',
        'subject_id' => $subject->id,
        'pendidikan_terakhir' => 'S1',
        'jurusan_pendidikan' => 'Teknik',
        'tahun_mulai_kerja' => 2020,
    ];
    
    echo "Testing with data:\n";
    foreach ($testData as $key => $value) {
        echo "  - {$key}: {$value}\n";
    }
    
    echo "\nStep 2: Run Validation\n";
    echo "-------------------------------------\n";
    
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users_central,email',
        'username' => 'required|string|max:255|unique:users_central,username',
        'password' => 'required|string|min:8|confirmed',
        'phone' => 'nullable|string|max:20',
        'nip' => 'required|string|max:50|unique:gurus,nip',
        'jenis_kelamin' => 'nullable|in:L,P',
        'tempat_lahir' => 'nullable|string|max:100',
        'tanggal_lahir' => 'nullable|date',
        'alamat' => 'nullable|string',
        'email_pribadi' => 'nullable|email|max:255',
        'subject_id' => 'required|exists:subjects,id',
        'pendidikan_terakhir' => 'nullable|string|max:255',
        'jurusan_pendidikan' => 'nullable|string|max:255',
        'tahun_mulai_kerja' => 'nullable|integer|min:1900|max:' . date('Y'),
    ];
    
    $validator = \Illuminate\Support\Facades\Validator::make($testData, $rules);
    
    if ($validator->fails()) {
        echo "❌ Validation FAILED:\n";
        foreach ($validator->errors()->all() as $error) {
            echo "  - {$error}\n";
        }
        return;
    } else {
        echo "✅ Validation PASSED\n";
    }
    
    echo "\nStep 3: Test Controller with Error Logging\n";
    echo "-------------------------------------\n";
    
    // Create mock request
    $request = new \Illuminate\Http\Request();
    $request->merge($testData);
    
    // Create controller with error logging
    $controller = new class extends \App\Http\Controllers\Admin\ModernUserController {
        public function storeGuruWithLogging(\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse
        {
            echo "🔍 Starting storeGuru with logging...\n";
            
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users_central,email',
                'username' => 'required|string|max:255|unique:users_central,username',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'nullable|string|max:20',
                'nip' => 'required|string|max:50|unique:gurus,nip',
                'jenis_kelamin' => 'nullable|in:L,P',
                'tempat_lahir' => 'nullable|string|max:100',
                'tanggal_lahir' => 'nullable|date',
                'alamat' => 'nullable|string',
                'email_pribadi' => 'nullable|email|max:255',
                'subject_id' => 'required|exists:subjects,id',
                'pendidikan_terakhir' => 'nullable|string|max:255',
                'jurusan_pendidikan' => 'nullable|string|max:255',
                'tahun_mulai_kerja' => 'nullable|integer|min:1900|max:' . date('Y'),
            ]);

            if ($validator->fails()) {
                echo "❌ Validator failed in controller\n";
                foreach ($validator->errors()->all() as $error) {
                    echo "  - {$error}\n";
                }
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            echo "✅ Validator passed in controller\n";
            
            \Illuminate\Support\Facades\DB::beginTransaction();
            try {
                echo "🔍 Creating user...\n";
                
                $user = \App\Models\UserCentral::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'username' => $request->username,
                    'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                    'role' => 'guru',
                    'phone' => $request->phone,
                    'is_active' => true,
                ]);
                
                echo "✅ User created: {$user->name} (ID: {$user->id})\n";
                
                echo "🔍 Creating guru profile...\n";
                
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
                
                echo "✅ Guru created: {$guru->name} (ID: {$guru->id})\n";
                
                \Illuminate\Support\Facades\DB::commit();
                
                echo "✅ Transaction committed\n";
                
                $redirect = new \Illuminate\Http\RedirectResponse(route('admin.users.guru'));
                $redirect->with('success', 'Guru berhasil ditambahkan');
                
                echo "✅ Redirect to: " . $redirect->getTargetUrl() . "\n";
                
                return $redirect;
                
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollback();
                echo "❌ Exception occurred: " . $e->getMessage() . "\n";
                echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
                
                $redirect = new \Illuminate\Http\RedirectResponse(route('admin.users.create.guru'));
                $redirect->with('error', 'Terjadi kesalahan saat menambahkan guru');
                $redirect->withInput();
                
                echo "❌ Redirect back to: " . $redirect->getTargetUrl() . "\n";
                
                return $redirect;
            }
        }
    };
    
    $result = $controller->storeGuruWithLogging($request);
    
    echo "\n🎯 FINAL RESULT:\n";
    echo "=====================================\n";
    echo "Redirect URL: {$result->getTargetUrl()}\n";
    
    if (str_contains($result->getTargetUrl(), 'admin/users/guru')) {
        echo "✅ SUCCESS! Should redirect to guru management page\n";
    } else {
        echo "❌ FAILED! Redirects back to create page\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
