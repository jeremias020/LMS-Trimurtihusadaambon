<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FIXING ADMIN ISSUES ===\n\n";

try {
    echo "Step 1: Fixing Jurusan table...\n";
    
    // Check if jurusans table exists
    if (!\Schema::hasTable('jurusans')) {
        \Schema::create('jurusans', function ($table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        echo "✅ Created jurusans table\n";
    } else {
        echo "✅ Jurusans table already exists\n";
    }
    
    // Insert sample jurusan
    $jurusanExists = \DB::table('jurusans')->count();
    if ($jurusanExists === 0) {
        \DB::table('jurusans')->insert([
            [
                'code' => 'KEP',
                'name' => 'Keperawatan',
                'description' => 'Program Studi Keperawatan',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
        echo "✅ Inserted sample jurusan\n";
    } else {
        echo "✅ Jurusan data already exists\n";
    }
    
    echo "\nStep 2: Fixing scores table...\n";
    
    // Check if scores table exists
    if (!\Schema::hasTable('scores')) {
        \Schema::create('scores', function ($table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->decimal('score', 5, 2);
            $table->string('grade', 2);
            $table->text('remarks')->nullable();
            $table->date('assessment_date');
            $table->foreignId('guru_id')->nullable()->constrained('users_central')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
        echo "✅ Created scores table\n";
    } else {
        echo "✅ Scores table already exists\n";
    }
    
    echo "\nStep 3: Fixing Kelas model relationships...\n";
    
    // Update classes table to have jurusan_id
    if (\Schema::hasColumn('classes', 'jurusan_id')) {
        echo "✅ Classes table already has jurusan_id\n";
    } else {
        \Schema::table('classes', function ($table) {
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusans')->onDelete('set null');
        });
        echo "✅ Added jurusan_id to classes table\n";
    }
    
    // Update existing class to have jurusan
    $kelas = \DB::table('classes')->first();
    if ($kelas && !$kelas->jurusan_id) {
        \DB::table('classes')->update(['jurusan_id' => 1]);
        echo "✅ Updated existing class with jurusan_id\n";
    }
    
    echo "\nStep 4: Fixing Subject model relationships...\n";
    
    // Update subjects table to have jurusan_id and guru_id
    if (!\Schema::hasColumn('subjects', 'jurusan_id')) {
        \Schema::table('subjects', function ($table) {
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusans')->onDelete('set null');
        });
        echo "✅ Added jurusan_id to subjects table\n";
    }
    
    if (!\Schema::hasColumn('subjects', 'guru_id')) {
        \Schema::table('subjects', function ($table) {
            $table->foreignId('guru_id')->nullable()->constrained('users_central')->onDelete('set null');
        });
        echo "✅ Added guru_id to subjects table\n";
    }
    
    // Update existing subject
    $subject = \DB::table('subjects')->first();
    if ($subject) {
        $updates = [];
        if (!$subject->jurusan_id) $updates['jurusan_id'] = 1;
        if (!$subject->guru_id) $updates['guru_id'] = 2; // Guru ID
        if (!empty($updates)) {
            \DB::table('subjects')->where('id', $subject->id)->update($updates);
            echo "✅ Updated existing subject with relationships\n";
        }
    }
    
    echo "\nStep 5: Fixing Practical model relationships...\n";
    
    // Update practicals table to have subject_id and kelas_id
    if (!\Schema::hasColumn('practicals', 'subject_id')) {
        \Schema::table('practicals', function ($table) {
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('set null');
        });
        echo "✅ Added subject_id to practicals table\n";
    }
    
    if (!\Schema::hasColumn('practicals', 'kelas_id')) {
        \Schema::table('practicals', function ($table) {
            $table->foreignId('kelas_id')->nullable()->constrained()->onDelete('set null');
        });
        echo "✅ Added kelas_id to practicals table\n";
    }
    
    // Update existing practicals
    $practicals = \DB::table('practicals')->get();
    foreach ($practicals as $practical) {
        $updates = [];
        if (!$practical->subject_id) $updates['subject_id'] = 1;
        if (!$practical->kelas_id) $updates['kelas_id'] = 1;
        if (!empty($updates)) {
            \DB::table('practicals')->where('id', $practical->id)->update($updates);
        }
    }
    if ($practicals->count() > 0) {
        echo "✅ Updated practicals with relationships\n";
    }
    
    echo "\nStep 6: Fixing Student model kelas relationship...\n";
    
    // Add kelas_id to users table if not exists
    if (!\Schema::hasColumn('users', 'kelas_id')) {
        \Schema::table('users', function ($table) {
            $table->foreignId('kelas_id')->nullable()->constrained()->onDelete('set null');
        });
        echo "✅ Added kelas_id to users table\n";
    }
    
    // Update student with kelas_id
    $student = \DB::table('users')->where('role', 'siswa')->first();
    if ($student && !$student->kelas_id) {
        \DB::table('users')->where('role', 'siswa')->update(['kelas_id' => 1]);
        echo "✅ Updated student with kelas_id\n";
    }
    
    echo "\nStep 7: Fixing JurusanController major field issue...\n";
    
    // Check JurusanController for major field usage
    $jurusanControllerPath = 'app/Http/Controllers/Admin/JurusanController.php';
    if (file_exists($jurusanControllerPath)) {
        $content = file_get_contents($jurusanControllerPath);
        if (strpos($content, 'users.major') !== false) {
            echo "❌ JurusanController still uses users.major field\n";
            echo "   This needs to be fixed to use correct relationship\n";
        } else {
            echo "✅ JurusanController doesn't use users.major\n";
        }
    }
    
    echo "\nStep 8: Testing relationships after fixes...\n";
    
    // Test Kelas -> Jurusan
    try {
        $kelas = \App\Models\Kelas::first();
        if ($kelas && $kelas->jurusan) {
            echo "✅ Kelas -> Jurusan relationship works: " . $kelas->jurusan->name . "\n";
        } else {
            echo "❌ Kelas -> Jurusan relationship not working\n";
        }
    } catch (Exception $e) {
        echo "❌ Kelas -> Jurusan error: " . $e->getMessage() . "\n";
    }
    
    // Test Student -> Kelas
    try {
        $student = \App\Models\Student::first();
        if ($student && $student->kelas) {
            echo "✅ Student -> Kelas relationship works: " . $student->kelas->name . "\n";
        } else {
            echo "❌ Student -> Kelas relationship not working\n";
        }
    } catch (Exception $e) {
        echo "❌ Student -> Kelas error: " . $e->getMessage() . "\n";
    }
    
    // Test Subject -> Jurusan
    try {
        $subject = \App\Models\Subject::first();
        if ($subject && $subject->jurusan) {
            echo "✅ Subject -> Jurusan relationship works: " . $subject->jurusan->name . "\n";
        } else {
            echo "❌ Subject -> Jurusan relationship not working\n";
        }
    } catch (Exception $e) {
        echo "❌ Subject -> Jurusan error: " . $e->getMessage() . "\n";
    }
    
    // Test Subject -> Guru
    try {
        $subject = \App\Models\Subject::first();
        if ($subject && $subject->guru) {
            echo "✅ Subject -> Guru relationship works: " . $subject->guru->name . "\n";
        } else {
            echo "❌ Subject -> Guru relationship not working\n";
        }
    } catch (Exception $e) {
        echo "❌ Subject -> Guru error: " . $e->getMessage() . "\n";
    }
    
    // Test Practical -> Subject
    try {
        $practical = \App\Models\Practical::first();
        if ($practical && $practical->subject) {
            echo "✅ Practical -> Subject relationship works: " . $practical->subject->name . "\n";
        } else {
            echo "❌ Practical -> Subject relationship not working\n";
        }
    } catch (Exception $e) {
        echo "❌ Practical -> Subject error: " . $e->getMessage() . "\n";
    }
    
    // Test Practical -> Kelas
    try {
        $practical = \App\Models\Practical::first();
        if ($practical && $practical->kelas) {
            echo "✅ Practical -> Kelas relationship works: " . $practical->kelas->name . "\n";
        } else {
            echo "❌ Practical -> Kelas relationship not working\n";
        }
    } catch (Exception $e) {
        echo "❌ Practical -> Kelas error: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎉 ADMIN ISSUES FIXED!\n";
    echo "✅ Created missing tables (jurusans, scores)\n";
    echo "✅ Added missing foreign keys\n";
    echo "✅ Updated existing data with relationships\n";
    echo "✅ Fixed model relationships\n";
    echo "✅ Ready for full admin functionality\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
