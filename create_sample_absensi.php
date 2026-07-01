<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🎯 CREATE SAMPLE ABSENSI DATA\n";
echo "=====================================\n";

try {
    // Get siswa user
    $siswaUser = \App\Models\User::where('role', 'siswa')->first();
    if (!$siswaUser) {
        echo "❌ No siswa user found\n";
        return;
    }
    
    $siswaId = $siswaUser->id;
    echo "✅ Creating sample data for: {$siswaUser->name} (ID: {$siswaId})\n";
    
    // Get class subjects first
    $classSubjects = \App\Models\ClassSubject::limit(5)->get();
    if ($classSubjects->count() === 0) {
        echo "❌ No class subjects found\n";
        return;
    }
    
    echo "✅ Found {$classSubjects->count()} class subjects\n";
    
    // Get subjects (for display)
    $subjects = \App\Models\Subject::limit(5)->get();
    if ($subjects->count() === 0) {
        echo "❌ No subjects found\n";
        return;
    }
    
    echo "✅ Found {$subjects->count()} subjects\n";
    
    // Clear existing attendance for this month
    $month = 4; // April
    $year = 2026;
    
    \App\Models\Attendance::where('siswa_id', $siswaId)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->delete();
    
    echo "✅ Cleared existing attendance for April 2026\n";
    
    // Create sample attendance data
    $statuses = ['present', 'present', 'present', 'sick', 'izin', 'present', 'present'];
    $keterangan = [
        'Hadir tepat waktu',
        'Hadir tepat waktu',
        'Hadir tepat waktu', 
        'Sakit demam',
        'Izin keluarga',
        'Hadir tepat waktu',
        'Hadir tepat waktu'
    ];
    
    $created = 0;
    for ($day = 1; $day <= min(7, date('t')); $day++) {
        $date = \Carbon\Carbon::create($year, $month, $day);
        
        // Skip weekends
        if ($date->isWeekend()) {
            continue;
        }
        
        $classSubject = $classSubjects->random();
        $subject = $subjects->random();
        $status = $statuses[$day - 1] ?? 'present';
        
        $attendance = \App\Models\Attendance::create([
            'siswa_id' => $siswaId,
            'student_id' => $siswaId,
            'class_subject_id' => $classSubject->id,
            'subject_id' => $subject->id,
            'date' => $date,
            'status' => $status,
            'waktu_masuk' => $status === 'present' ? '08:00' : null,
            'waktu_keluar' => $status === 'present' ? '14:00' : null,
            'note' => $keterangan[$day - 1] ?? 'Hadir',
            'created_by' => 1, // Admin or teacher ID
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        $created++;
        echo "  ✅ Day {$day}: {$subject->name} - {$status}\n";
    }
    
    echo "\n✅ Created {$created} attendance records\n";
    
    // Test the data
    echo "\nStep 2: Test Data Retrieval\n";
    echo "-------------------------------------\n";
    
    $attendances = \App\Models\Attendance::with('subject')
        ->where('siswa_id', $siswaId)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->orderBy('date')
        ->get();
    
    echo "Retrieved {$attendances->count()} records:\n";
    foreach ($attendances as $attendance) {
        echo "  - {$attendance->date->format('d M Y')} | {$attendance->status} | " . 
             ($attendance->subject ? $attendance->subject->name : 'No Subject') . "\n";
    }
    
    // Test stats calculation
    echo "\nStep 3: Test Stats Calculation\n";
    echo "-------------------------------------\n";
    
    $stats = \App\Models\Attendance::selectRaw('status, COUNT(*) as count')
        ->where('siswa_id', $siswaId)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->groupBy('status')
        ->get();
    
    echo "Stats breakdown:\n";
    $total = 0;
    foreach ($stats as $stat) {
        echo "  {$stat->status}: {$stat->count}\n";
        $total += $stat->count;
    }
    
    $present = $stats->where('status', 'present')->first()?->count ?? 0;
    $percentage = $total > 0 ? round(($present / $total) * 100, 2) : 0;
    
    echo "  Total: {$total}\n";
    echo "  Present: {$present}\n";
    echo "  Percentage: {$percentage}%\n";
    
    echo "\n🎯 SAMPLE DATA READY!\n";
    echo "=====================================\n";
    echo "✅ Sample attendance data created\n";
    echo "✅ Data retrieval working\n";
    echo "✅ Stats calculation working\n";
    echo "✅ Ready for testing absensi page\n";
    
    echo "\n🚀 NEXT STEPS:\n";
    echo "=====================================\n";
    echo "1. Visit: http://127.0.0.1:8000/siswa/absensi\n";
    echo "2. Filter to April 2026\n";
    echo "3. Check table display\n";
    echo "4. Verify stats calculations\n";
    echo "5. Test chart rendering\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
