<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🧪 SIMULATE GURU FORM SUBMISSION\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check Available Subjects\n";
    echo "-------------------------------------\n";
    
    $subjects = \App\Models\Subject::where('is_active', 1)->get();
    echo "Available subjects: " . $subjects->count() . "\n";
    
    if ($subjects->isEmpty()) {
        echo "❌ No subjects found! This could cause validation error\n";
        return;
    }
    
    $firstSubject = $subjects->first();
    echo "First subject: {$firstSubject->name} (ID: {$firstSubject->id})\n";
    
    echo "\nStep 2: Prepare Test Data\n";
    echo "-------------------------------------\n";
    
    $testData = [
        'name' => 'Test Guru ' . time(),
        'email' => 'testguru' . time() . '@example.com',
        'username' => 'testguru' . time(),
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone' => '08123456789',
        'nip' => str_shuffle('1234567890123456'),
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '1990-01-01',
        'alamat' => 'Jakarta',
        'email_pribadi' => 'personal@example.com',
        'subject_id' => $firstSubject->id,
        'pendidikan_terakhir' => 'S1',
        'jurusan_pendidikan' => 'Teknik',
        'tahun_mulai_kerja' => 2020,
    ];
    
    echo "Test data prepared:\n";
    foreach ($testData as $key => $value) {
        echo "  - {$key}: {$value}\n";
    }
    
    echo "\nStep 3: Test Validation Rules\n";
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
    
    echo "\nStep 4: Test Actual Controller Method\n";
    echo "-------------------------------------\n";
    
    // Create a mock request
    $request = new \Illuminate\Http\Request();
    $request->merge($testData);
    
    // Test the controller method
    $controller = new \App\Http\Controllers\Admin\ModernUserController();
    
    echo "Calling storeGuru method...\n";
    
    try {
        // This will try to actually create the records
        $result = $controller->storeGuru($request);
        
        echo "✅ Controller method executed successfully\n";
        echo "Result type: " . get_class($result) . "\n";
        
        if ($result instanceof \Illuminate\Http\RedirectResponse) {
            echo "Redirect target: " . $result->getTargetUrl() . "\n";
            
            // Check session for success message
            $session = $result->getSession();
            if ($session && $session->has('success')) {
                echo "Success message: " . $session->get('success') . "\n";
            }
            
            if ($session && $session->has('error')) {
                echo "Error message: " . $session->get('error') . "\n";
            }
        }
        
    } catch (\Exception $e) {
        echo "❌ Controller method FAILED with exception:\n";
        echo "Error: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
        
        // Check if this is a database error
        if (str_contains($e->getMessage(), 'SQLSTATE')) {
            echo "🔍 This appears to be a database error\n";
        }
        
        // Check if this is a constraint error
        if (str_contains($e->getMessage(), 'Integrity constraint')) {
            echo "🔍 This appears to be a constraint violation\n";
        }
    }
    
    echo "\nStep 5: Check Database State\n";
    echo "-------------------------------------\n";
    
    // Check if user was created
    $userEmail = $testData['email'];
    $user = \App\Models\UserCentral::where('email', $userEmail)->first();
    
    if ($user) {
        echo "✅ User was created: {$user->name} (ID: {$user->id})\n";
        
        // Check if guru profile was created
        $guru = \App\Models\Guru::where('user_id', $user->id)->first();
        if ($guru) {
            echo "✅ Guru profile was created: {$guru->name} (NIP: {$guru->nip})\n";
            echo "  - Mata Pelajaran: {$guru->mata_pelajaran}\n";
            echo "  - Status: {$guru->status}\n";
        } else {
            echo "❌ Guru profile was NOT created\n";
        }
    } else {
        echo "❌ User was NOT created\n";
    }
    
    echo "\n🔍 ANALYSIS:\n";
    echo "=====================================\n";
    
    if (isset($result) && $result instanceof \Illuminate\Http\RedirectResponse) {
        $targetUrl = $result->getTargetUrl();
        
        if (str_contains($targetUrl, 'admin/users/guru')) {
            echo "✅ Redirect is CORRECT - going to guru management page\n";
        } elseif (str_contains($targetUrl, 'create/guru')) {
            echo "❌ Redirect is WRONG - going back to create page\n";
            echo "This suggests validation error or exception occurred\n";
        } else {
            echo "❓ Redirect to unknown location: {$targetUrl}\n";
        }
    }
    
    echo "\n📋 POSSIBLE ISSUES:\n";
    echo "=====================================\n";
    echo "1. Database connection issues\n";
    echo "2. Missing required columns in tables\n";
    echo "3. Constraint violations\n";
    echo "4. Validation errors not caught earlier\n";
    echo "5. Exception being caught and redirecting back\n";
    
} catch (Exception $e) {
    echo "❌ Fatal error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
