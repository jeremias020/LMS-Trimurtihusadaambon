<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

// Check NilaiPraktik data
echo "=== DEBUG DATA PRAKTIKUM ===\n";

// 1. Check total records
$totalNilaiPraktik = \App\Models\NilaiPraktik::count();
echo "Total NilaiPraktik records: {$totalNilaiPraktik}\n";

// 2. Check current guru
$guruId = \Auth::id();
echo "Current Guru ID: {$guruId}\n";

// 3. Check nilai praktik for current guru
$guruNilaiPraktik = \App\Models\NilaiPraktik::where('guru_id', $guruId)->count();
echo "NilaiPraktik for current guru: {$guruNilaiPraktik}\n";

// 4. Check Ahmad Saputra
$ahmadSaputra = \App\Models\User::where('name', 'like', '%Ahmad Saputra%')->first();
if ($ahmadSaputra) {
    echo "Ahmad Saputra found - ID: {$ahmadSaputra->id}\n";
    echo "Ahmad Saputra class: " . ($ahmadSaputra->kelas->name ?? 'No class') . "\n";
    
    // Check nilai praktik for Ahmad Saputra
    $ahmadNilai = \App\Models\NilaiPraktik::where('siswa_id', $ahmadSaputra->id)->count();
    echo "NilaiPraktik for Ahmad Saputra: {$ahmadNilai}\n";
    
    // Show details if any
    if ($ahmadNilai > 0) {
        $details = \App\Models\NilaiPraktik::where('siswa_id', $ahmadSaputra->id)->get();
        foreach ($details as $detail) {
            echo "- {$detail->mata_praktik} ({$detail->tanggal_praktik}) - Nilai: {$detail->total_nilai}\n";
        }
    }
} else {
    echo "Ahmad Saputra not found in database\n";
}

// 5. Check all students with name Ahmad
$ahmadStudents = \App\Models\User::where('name', 'like', '%Ahmad%')->get();
echo "\nStudents with 'Ahmad' in name:\n";
foreach ($ahmadStudents as $student) {
    echo "- {$student->name} (ID: {$student->id}) - Class: " . ($student->kelas->name ?? 'No class') . "\n";
}

// 6. Check assignment submissions for Ahmad Saputra
if ($ahmadSaputra) {
    $assignmentSubmissions = \App\Models\AssignmentSubmission::where('siswa_id', $ahmadSaputra->id)->count();
    echo "\nAssignment submissions for Ahmad Saputra: {$assignmentSubmissions}\n";
}

echo "\n=== END DEBUG ===\n";
