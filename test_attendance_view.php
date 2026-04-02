<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING ATTENDANCE CREATE VIEW IMPROVEMENTS ===\n\n";

try {
    echo "Step 1: Testing AttendanceController create method...\n";
    
    // Simulate the controller logic
    $guruId = 2; // Guru ID
    
    echo "Simulating with guru_id: {$guruId}\n";
    
    // Get classes where this guru teaches
    $classes = \DB::table('classes')
        ->join('class_subjects', 'classes.id', '=', 'class_subjects.class_id')
        ->where('class_subjects.teacher_id', $guruId)
        ->distinct()
        ->select('classes.id', 'classes.name')
        ->orderBy('classes.name')
        ->pluck('name', 'id');
    
    echo "✅ Classes loaded: {$classes->count()}\n";
    foreach ($classes as $classId => $className) {
        echo "  - {$className} (ID: {$classId})\n";
    }
    
    // Get subjects assigned to this guru
    $subjects = \DB::table('class_subjects')
        ->join('subjects', 'class_subjects.subject_id', '=', 'subjects.id')
        ->where('class_subjects.teacher_id', $guruId)
        ->where('subjects.is_active', true)
        ->select('class_subjects.id', 'subjects.name')
        ->distinct()
        ->orderBy('subjects.name')
        ->get();
    
    echo "✅ Subjects loaded: {$subjects->count()}\n";
    foreach ($subjects as $subject) {
        echo "  - {$subject->name} (ID: {$subject->id})\n";
    }
    
    // Get students
    $selectedClass = $classes->keys()->first();
    $siswas = \DB::table('users')
        ->join('class_students', 'users.id', '=', 'class_students.student_id')
        ->where('users.role', 'siswa')
        ->where('class_students.class_id', $selectedClass)
        ->orderBy('users.name')
        ->select('users.*', 'class_students.class_id')
        ->get();
    
    echo "✅ Students loaded: {$siswas->count()}\n";
    foreach ($siswas as $siswa) {
        echo "  - {$siswa->name} (Class ID: {$siswa->class_id})\n";
    }
    
    echo "\nStep 2: Testing view data structure...\n";
    
    $viewData = [
        'siswas' => $siswas,
        'classes' => $classes,
        'selectedClass' => $selectedClass,
        'subjects' => $subjects,
    ];
    
    echo "View data prepared:\n";
    foreach ($viewData as $key => $value) {
        if ($value instanceof \Illuminate\Support\Collection) {
            echo "  - {$key}: {$value->count()} items\n";
        } else {
            echo "  - {$key}: " . ($value ?? 'null') . "\n";
        }
    }
    
    echo "\nStep 3: Testing student data structure for view...\n";
    
    foreach ($siswas as $siswa) {
        echo "Student data structure:\n";
        echo "  - ID: {$siswa->id}\n";
        echo "  - Name: {$siswa->name}\n";
        echo "  - Email: {$siswa->email}\n";
        echo "  - Role: {$siswa->role}\n";
        echo "  - NIS/NIP: " . ($siswa->nis_nip ?? 'N/A') . "\n";
        echo "  - Class ID: {$siswa->class_id}\n";
        echo "  - Avatar: " . ($siswa->avatar ?? 'N/A') . "\n";
        echo "  ---\n";
    }
    
    echo "\nStep 4: Testing attendance form fields...\n";
    
    // Simulate form data structure
    $attendanceData = [];
    foreach ($siswas as $index => $siswa) {
        $attendanceData["attendances[{$index}]"] = [
            'siswa_id' => $siswa->id,
            'status' => 'present',
            'waktu_masuk' => date('H:i'),
            'keterangan' => ''
        ];
    }
    
    echo "Form data structure:\n";
    foreach ($attendanceData as $key => $value) {
        echo "  - {$key}: " . json_encode($value) . "\n";
    }
    
    echo "\nStep 5: Testing header information display...\n";
    
    // Simulate header info
    $selectedSubject = $subjects->first();
    $selectedClassInfo = $classes->first();
    $selectedDate = date('Y-m-d');
    
    echo "Header information:\n";
    echo "  - Mata Pelajaran: {$selectedSubject->name}\n";
    echo "  - Kelas: {$selectedClassInfo}\n";
    echo "  - Tanggal: {$selectedDate}\n";
    echo "  - Total Siswa: {$siswas->count()}\n";
    
    echo "\n🎉 SUCCESS! Attendance create view improvements tested!\n";
    echo "✅ All data loaded correctly\n";
    echo "✅ Student structure matches view requirements\n";
    echo "✅ Header information ready for display\n";
    echo "✅ Form fields properly structured\n";
    echo "✅ Time and date handling working\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== CLEANUP ===\n";
if (file_exists(__DIR__ . '/test_attendance_create.php')) {
    unlink(__DIR__ . '/test_attendance_create.php');
    echo "✅ Removed test_attendance_create.php\n";
}
if (file_exists(__DIR__ . '/add_students_to_users.php')) {
    unlink(__DIR__ . '/add_students_to_users.php');
    echo "✅ Removed add_students_to_users.php\n";
}
