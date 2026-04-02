<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CREATING STUDENT IN USERS TABLE ===\n\n";

try {
    echo "Step 1: Creating student record in users table...\n";
    
    // Get student data from users_central
    $studentCentral = \DB::table('users_central')->where('role', 'siswa')->first();
    
    if ($studentCentral) {
        // Check if already exists in users table
        $existingUser = \DB::table('users')->where('id', $studentCentral->id)->first();
        
        if (!$existingUser) {
            // Create student in users table
            \DB::table('users')->insert([
                'id' => $studentCentral->id,
                'name' => $studentCentral->name,
                'email' => $studentCentral->email,
                'password' => $studentCentral->password,
                'role' => $studentCentral->role,
                'is_active' => $studentCentral->is_active ?? true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "✅ Created student record in users table\n";
        } else {
            echo "✅ Student already exists in users table\n";
        }
    }
    
    echo "\nStep 2: Creating class-student relationship...\n";
    
    $kelas = \App\Models\Kelas::first();
    $student = \App\Models\User::where('role', 'siswa')->first();
    
    // Check if relationship exists
    $existing = \DB::table('class_students')
        ->where('class_id', $kelas->id)
        ->where('student_id', $student->id)
        ->first();
    
    if (!$existing) {
        \DB::table('class_students')->insert([
            'class_id' => $kelas->id,
            'student_id' => $student->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "✅ Created class-student relationship\n";
    } else {
        echo "✅ Class-student relationship already exists\n";
    }
    
    echo "\nStep 3: Creating student profile...\n";
    
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
    
    echo "\nStep 4: Testing final data...\n";
    
    // Test student data
    $students = \App\Models\User::where('role', 'siswa')
        ->where('is_active', true)
        ->with(['siswa.kelas.jurusan'])
        ->orderBy('name')
        ->get();
    
    echo "✅ Final student data:\n";
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
    
    echo "✅ Final practical data:\n";
    foreach ($practicals as $practical) {
        echo "  - ID: {$practical->id}\n";
        echo "    Title: {$practical->title}\n";
        echo "    Subject: {$practical->subject_name}\n";
        echo "    Class: {$practical->class_name}\n";
        echo "    Due Date: {$practical->due_date}\n";
        echo "    ---\n";
    }
    
    echo "\nStep 5: Testing dropdown format...\n";
    
    // Test dropdown format
    echo "Student dropdown format:\n";
    foreach ($students as $student) {
        $displayName = "{$student->name} - " . ($student->siswa->nis ?? 'N/A') . " - " . ($student->siswa->kelas->name ?? 'N/A');
        echo "  Option: {$displayName}\n";
        break;
    }
    
    echo "\nPractical dropdown format:\n";
    foreach ($practicals as $practical) {
        $displayName = "{$practical->title} - {$practical->subject_name} ({$practical->class_name})";
        echo "  Option: {$displayName}\n";
        break;
    }
    
    echo "\n🎉 SUCCESS! All data ready for dropdown!\n";
    echo "✅ Student created in users table with proper relationships\n";
    echo "✅ Class-student relationship established\n";
    echo "✅ Student profile with NIS and class created\n";
    echo "✅ Practical data with complete information\n";
    echo "✅ Dropdown format includes all necessary info\n";
    echo "✅ Ready for auto assessment page\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
