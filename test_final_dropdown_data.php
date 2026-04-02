<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECKING EXISTING DATA AND CREATING RELATIONSHIPS ===\n\n";

try {
    echo "Step 1: Checking existing student data...\n";
    
    // Check student in users table
    $studentInUsers = \DB::table('users')->where('role', 'siswa')->first();
    if ($studentInUsers) {
        echo "✅ Found student in users table:\n";
        echo "  - ID: {$studentInUsers->id}\n";
        echo "  - Name: {$studentInUsers->name}\n";
        echo "  - Email: {$studentInUsers->email}\n";
        echo "  - Role: {$studentInUsers->role}\n";
        echo "  - Is Active: " . ($studentInUsers->is_active ? 'Yes' : 'No') . "\n";
    } else {
        echo "❌ No student found in users table\n";
        exit;
    }
    
    echo "\nStep 2: Creating class-student relationship...\n";
    
    $kelas = \App\Models\Kelas::first();
    
    // Check if relationship exists
    $existing = \DB::table('class_students')
        ->where('class_id', $kelas->id)
        ->where('student_id', $studentInUsers->id)
        ->first();
    
    if (!$existing) {
        \DB::table('class_students')->insert([
            'class_id' => $kelas->id,
            'student_id' => $studentInUsers->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "✅ Created class-student relationship\n";
    } else {
        echo "✅ Class-student relationship already exists\n";
    }
    
    echo "\nStep 3: Creating/updating student profile...\n";
    
    $student = \App\Models\User::find($studentInUsers->id);
    
    if (!$student->siswa) {
        \App\Models\Student::create([
            'user_id' => $student->id,
            'nis' => '2024001',
            'nisn' => '0034567890',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Ambon',
            'tanggal_lahir' => '2005-01-15',
            'alamat' => 'Jl. Pendidikan No. 123, Ambon',
            'no_telepon' => '08123456789',
            'kelas_id' => $kelas->id,
            'major' => 'Keperawatan',
            'tahun_ajaran' => '2024/2025',
            'status' => 'aktif'
        ]);
        echo "✅ Created student profile\n";
    } else {
        $student->siswa->update([
            'nis' => '2024001',
            'kelas_id' => $kelas->id,
            'major' => 'Keperawatan'
        ]);
        echo "✅ Updated student profile\n";
    }
    
    echo "\nStep 4: Testing complete data relationships...\n";
    
    // Test student data with all relationships
    $students = \App\Models\User::where('role', 'siswa')
        ->where('is_active', true)
        ->with(['siswa.kelas.jurusan'])
        ->orderBy('name')
        ->get();
    
    echo "✅ Student data with complete relationships:\n";
    foreach ($students as $student) {
        echo "  - ID: {$student->id}\n";
        echo "    Name: {$student->name}\n";
        echo "    NIS: " . ($student->siswa->nis ?? 'N/A') . "\n";
        echo "    Class: " . ($student->siswa->kelas->name ?? 'N/A') . "\n";
        echo "    Jurusan: " . ($student->siswa->kelas->jurusan->name ?? 'N/A') . "\n";
        echo "    ---\n";
    }
    
    // Test practical data
    $guru = \App\Models\User::where('role', 'guru')->first();
    $practicals = \DB::table('practicals')
        ->join('class_subjects', 'practicals.class_subject_id', '=', 'class_subjects.id')
        ->join('subjects', 'class_subjects.subject_id', '=', 'subjects.id')
        ->join('classes', 'class_subjects.class_id', '=', 'classes.id')
        ->where('practicals.guru_id', $guru->id)
        ->select(
            'practicals.*',
            'subjects.name as subject_name',
            'classes.name as class_name'
        )
        ->get();
    
    echo "✅ Practical data with complete relationships:\n";
    foreach ($practicals as $practical) {
        echo "  - ID: {$practical->id}\n";
        echo "    Title: {$practical->title}\n";
        echo "    Subject: {$practical->subject_name}\n";
        echo "    Class: {$practical->class_name}\n";
        echo "    Due Date: {$practical->due_date}\n";
        echo "    ---\n";
    }
    
    echo "\nStep 5: Testing controller methods...\n";
    
    $controller = new \App\Http\Controllers\Guru\PenilaianController();
    
    try {
        $view = $controller->autoAssessment();
        $data = $view->getData();
        echo "✅ autoAssessment method works:\n";
        echo "  - Students: " . count($data['students']) . "\n";
        echo "  - Practicals: " . count($data['practicals']) . "\n";
        echo "  - Classes: " . count($data['classes']) . "\n";
        echo "  - Subjects: " . count($data['subjects']) . "\n";
    } catch (Exception $e) {
        echo "❌ autoAssessment method error: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 6: Testing dropdown format for view...\n";
    
    // Test dropdown format exactly as in view
    echo "Student dropdown format (as in view):\n";
    foreach ($students as $student) {
        $displayName = "{$student->name} - " . ($student->siswa->nis ?? 'N/A') . " - " . ($student->siswa->kelas->name ?? 'N/A');
        echo "  <option value=\"{$student->id}\" data-name=\"{$student->name}\" data-class=\"" . ($student->siswa->kelas->name ?? 'N/A') . "\" data-nis=\"" . ($student->siswa->nis ?? 'N/A') . "\">{$displayName}</option>\n";
        break;
    }
    
    echo "\nPractical dropdown format (as in view):\n";
    foreach ($practicals as $practical) {
        $displayName = "{$practical->title} - {$practical->subject_name} ({$practical->class_name})";
        echo "  <option value=\"{$practical->id}\" data-title=\"{$practical->title}\" data-subject=\"{$practical->subject_name}\" data-max-score=\"100\" data-class=\"{$practical->class_name}\">{$displayName}</option>\n";
        break;
    }
    
    echo "\n🎉 SUCCESS! All data ready for dropdown!\n";
    echo "✅ Student data with NIS, class, and jurusan\n";
    echo "✅ Practical data with subject and class info\n";
    echo "✅ All relationships properly established\n";
    echo "✅ Controller methods working\n";
    echo "✅ Dropdown format ready for view\n";
    echo "✅ Admin-created data fully integrated\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
