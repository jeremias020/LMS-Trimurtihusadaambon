<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 CHECKING DATABASE TABLE STRUCTURES\n";
echo "=====================================\n";

// Check practicals table
echo "\n1. PRACTICALS TABLE STRUCTURE:\n";
try {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('practicals');
    echo "  Columns found: " . implode(', ', $columns) . "\n";
    
    // Check if specific columns exist
    $hasIsPublished = in_array('is_published', $columns);
    $hasKelasId = in_array('kelas_id', $columns);
    $hasDeletedAt = in_array('deleted_at', $columns);
    
    echo "  has is_published: " . ($hasIsPublished ? "✅ Yes" : "❌ No") . "\n";
    echo "  has kelas_id: " . ($hasKelasId ? "✅ Yes" : "❌ No") . "\n";
    echo "  has deleted_at: " . ($hasDeletedAt ? "✅ Yes" : "❌ No") . "\n";
} catch (\Exception $e) {
    echo "  ❌ Error: " . $e->getMessage() . "\n";
}

// Check students table
echo "\n2. STUDENTS TABLE STRUCTURE:\n";
try {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('students');
    echo "  Columns found: " . implode(', ', $columns) . "\n";
    
    // Check if specific columns exist
    $hasFoto = in_array('foto', $columns);
    $hasNis = in_array('nis', $columns);
    $hasName = in_array('name', $columns);
    
    echo "  has foto: " . ($hasFoto ? "✅ Yes" : "❌ No") . "\n";
    echo "  has nis: " . ($hasNis ? "✅ Yes" : "❌ No") . "\n";
    echo "  has name: " . ($hasName ? "✅ Yes" : "❌ No") . "\n";
} catch (\Exception $e) {
    echo "  ❌ Error: " . $e->getMessage() . "\n";
}

// Check if Guru ScoringController exists
echo "\n3. GURU SCORING CONTROLLER:\n";
try {
    $controllerExists = class_exists('App\Http\Controllers\Guru\ScoringController');
    echo "  Controller exists: " . ($controllerExists ? "✅ Yes" : "❌ No") . "\n";
    
    if (!$controllerExists) {
        // Check if there's a similar controller
        $files = glob('app/Http/Controllers/Guru/*Scoring*.php');
        echo "  Similar controllers found: " . (count($files) > 0 ? implode(', ', $files) : "None") . "\n";
    }
} catch (\Exception $e) {
    echo "  ❌ Error: " . $e->getMessage() . "\n";
}

// Check all guru controllers
echo "\n4. ALL GURU CONTROLLERS:\n";
try {
    $files = glob('app/Http/Controllers/Guru/*.php');
    foreach ($files as $file) {
        $filename = basename($file, '.php');
        echo "  - {$filename}\n";
    }
} catch (\Exception $e) {
    echo "  ❌ Error: " . $e->getMessage() . "\n";
}

// Check sample data
echo "\n5. SAMPLE DATA CHECK:\n";
try {
    $siswaCount = \App\Models\User::where('role', 'siswa')->count();
    $guruCount = \App\Models\User::where('role', 'guru')->count();
    $adminCount = \App\Models\User::where('role', 'admin')->count();
    
    echo "  Siswa users: {$siswaCount}\n";
    echo "  Guru users: {$guruCount}\n";
    echo "  Admin users: {$adminCount}\n";
    
    $studentCount = \App\Models\Student::count();
    echo "  Student records: {$studentCount}\n";
    
    $practicalCount = \Illuminate\Support\Facades\DB::table('practicals')->count();
    echo "  Practical records: {$practicalCount}\n";
} catch (\Exception $e) {
    echo "  ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n\n🎯 DIAGNOSTIC SUMMARY:\n";
echo "=====================================\n";
echo "✅ Database structure check complete\n";
echo "✅ Controller existence check complete\n";
echo "✅ Sample data check complete\n";
?>
