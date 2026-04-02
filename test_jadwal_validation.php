<?php
echo "=== TESTING JADWAL CREATION VALIDATION ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Testing validation rules...\n";
    
    // Test 1: Check required fields
    $requiredFields = ['title', 'exam_type', 'subject_id', 'start_time', 'end_time', 'duration_minutes'];
    echo "Required fields: " . implode(', ', $requiredFields) . "\n";
    
    // Test 2: Check if subjects exist
    $subjects = \App\Models\Subject::where('is_active', true)->get();
    echo "✅ Active subjects: " . $subjects->count() . "\n";
    if ($subjects->isEmpty()) {
        echo "❌ NO ACTIVE SUBJECTS FOUND - This could cause validation error!\n";
    } else {
        foreach ($subjects->take(3) as $subject) {
            echo "  - {$subject->nama} (ID: {$subject->id})\n";
        }
    }
    
    // Test 3: Check if kelas exist
    $kelas = \App\Models\Kelas::all();
    echo "✅ Kelas available: " . $kelas->count() . "\n";
    if ($kelas->isEmpty()) {
        echo "❌ NO KELAS FOUND - This could cause issues!\n";
    } else {
        foreach ($kelas->take(3) as $k) {
            echo "  - {$k->nama} (ID: {$k->id})\n";
        }
    }
    
    // Test 4: Simulate form submission
    echo "\n=== SIMULATING FORM SUBMISSION ===\n";
    
    $testData = [
        'title' => 'Test Jadwal Ujian',
        'description' => 'Test description',
        'exam_type' => 'quiz',
        'subject_id' => $subjects->first()->id ?? null,
        'kelas_id' => $kelas->first()->id ?? null,
        'start_time' => now()->addHours(1)->format('Y-m-d\TH:i'),
        'end_time' => now()->addHours(2)->format('Y-m-d\TH:i'),
        'duration_minutes' => 60,
        'location' => 'Ruang 201',
        'is_published' => false
    ];
    
    echo "Test data prepared:\n";
    foreach ($testData as $key => $value) {
        echo "  - $key: " . (is_bool($value) ? ($value ? 'true' : 'false') : $value) . "\n";
    }
    
    // Test 5: Check validation rules
    echo "\n=== CHECKING VALIDATION RULES ===\n";
    
    $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'exam_type' => 'required|in:uts,uas,quiz,praktikum,lainnya',
        'subject_id' => 'required|exists:subjects,id',
        'kelas_id' => 'nullable|exists:kelas,id',
        'start_time' => 'required|date|after:now',
        'end_time' => 'required|date|after:start_time',
        'location' => 'nullable|string|max:255',
        'duration_minutes' => 'required|integer|min:1',
        'is_published' => 'boolean',
    ];
    
    foreach ($rules as $field => $rule) {
        echo "  - $field: $rule\n";
    }
    
    // Test 6: Check potential issues
    echo "\n=== CHECKING POTENTIAL ISSUES ===\n";
    
    // Check if start_time is in the future
    $startTime = \Carbon\Carbon::parse($testData['start_time']);
    if ($startTime->isPast()) {
        echo "❌ Start time is in the past - validation will fail!\n";
    } else {
        echo "✅ Start time is in the future\n";
    }
    
    // Check if end_time is after start_time
    $endTime = \Carbon\Carbon::parse($testData['end_time']);
    if ($endTime->lte($startTime)) {
        echo "❌ End time is not after start time - validation will fail!\n";
    } else {
        echo "✅ End time is after start time\n";
    }
    
    // Check if subject exists
    if (!$testData['subject_id'] || !\App\Models\Subject::find($testData['subject_id'])) {
        echo "❌ Subject ID invalid - validation will fail!\n";
    } else {
        echo "✅ Subject ID is valid\n";
    }
    
    // Check if kelas exists (if provided)
    if ($testData['kelas_id'] && !\App\Models\Kelas::find($testData['kelas_id'])) {
        echo "❌ Kelas ID invalid - validation will fail!\n";
    } else {
        echo "✅ Kelas ID is valid (or null)\n";
    }
    
    echo "\n=== DEBUGGING TIPS ===\n";
    echo "1. Check browser console for JavaScript errors\n";
    echo "2. Check Laravel logs: storage/logs/laravel.log\n";
    echo "3. Add temporary debug in controller:\n";
    echo "   - Log::info('Form data: ' . json_encode(\$request->all()));\n";
    echo "   - Log::info('Validation errors: ' . json_encode(\$validator->errors()));\n";
    
    echo "\n=== COMMON ISSUES ===\n";
    echo "1. CSRF token mismatch\n";
    echo "2. start_time not in future\n";
    echo "3. end_time not after start_time\n";
    echo "4. subject_id doesn't exist\n";
    echo "5. kelas_id doesn't exist\n";
    echo "6. Missing required fields\n";
    
    echo "\n=== SOLUTIONS ===\n";
    echo "1. Add error display in create.blade.php:\n";
    echo "   @if(\$errors->any())\n";
    echo "       <div class=\"alert alert-danger\">\n";
    echo "           @foreach(\$errors->all() as \$error)\n";
    echo "               <p>{{ \$error }}</p>\n";
    echo "           @endforeach\n";
    echo "       </div>\n";
    echo "   @endif\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
