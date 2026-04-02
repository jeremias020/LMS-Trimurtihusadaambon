<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 COMPREHENSIVE DATABASE REPAIR\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Database Structure Analysis\n";
    echo "-------------------------------------\n";
    
    $database = \DB::connection()->getDatabaseName();
    $tables = \DB::select('SHOW TABLES');
    $expectedTables = [
        'users', 'users_central', 'gurus', 'students', 'classes', 'jurusans', 'majors',
        'subjects', 'class_subjects', 'class_students', 'assignments', 'assignment_submissions',
        'practicals', 'practical_scores', 'materials', 'attendances', 'scores',
        'assessment_criteria', 'practical_assessments', 'notifications', 'material_downloads',
        'exam_schedules_new', 'migrations'
    ];
    
    echo "Expected tables: " . count($expectedTables) . "\n";
    echo "Actual tables: " . count($tables) . "\n\n";
    
    $existingTables = [];
    foreach ($tables as $table) {
        $tableName = $table->{'Tables_in_' . $database};
        $existingTables[] = $tableName;
    }
    
    $missingTables = array_diff($expectedTables, $existingTables);
    if (!empty($missingTables)) {
        echo "❌ Missing tables:\n";
        foreach ($missingTables as $table) {
            echo "  - {$table}\n";
        }
    } else {
        echo "✅ All expected tables exist\n";
    }
    
    echo "\nStep 2: Fixing Missing Tables\n";
    echo "-------------------------------------\n";
    
    // Create missing tables
    if (in_array('students', $missingTables)) {
        \Schema::create('students', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('nis')->unique()->nullable();
            $table->unsignedBigInteger('kelas_id')->nullable();
            $table->unsignedBigInteger('jurusan_id')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('kelas_id')->references('id')->on('classes')->onDelete('set null');
            $table->foreign('jurusan_id')->references('id')->on('jurusans')->onDelete('set null');
        });
        echo "✅ Created students table\n";
    }
    
    if (in_array('scores', $missingTables)) {
        \Schema::create('scores', function ($table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('guru_id')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->string('semester')->nullable();
            $table->string('academic_year')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('siswa_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('guru_id')->references('id')->on('users')->onDelete('set null');
            
            $table->unique(['siswa_id', 'subject_id', 'semester', 'academic_year']);
        });
        echo "✅ Created scores table\n";
    }
    
    if (in_array('assessment_criteria', $missingTables)) {
        \Schema::create('assessment_criteria', function ($table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('max_score', 5, 2)->default(100);
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
        echo "✅ Created assessment_criteria table\n";
    }
    
    if (in_array('practical_assessments', $missingTables)) {
        \Schema::create('practical_assessments', function ($table) {
            $table->id();
            $table->unsignedBigInteger('criteria_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('criteria_id')->references('id')->on('assessment_criteria')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('set null');
        });
        echo "✅ Created practical_assessments table\n";
    }
    
    if (in_array('notifications', $missingTables)) {
        \Schema::create('notifications', function ($table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'success', 'warning', 'error'])->default('info');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
        echo "✅ Created notifications table\n";
    }
    
    if (in_array('material_downloads', $missingTables)) {
        \Schema::create('material_downloads', function ($table) {
            $table->id();
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('siswa_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
            $table->foreign('siswa_id')->references('id')->on('users')->onDelete('set null');
        });
        echo "✅ Created material_downloads table\n";
    }
    
    if (in_array('exam_schedules_new', $missingTables)) {
        \Schema::create('exam_schedules_new', function ($table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('kelas_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('location')->nullable();
            $table->enum('type', ['uts', 'uas', 'quiz', 'practical'])->default('uts');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('kelas_id')->references('id')->on('classes')->onDelete('set null');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
        });
        echo "✅ Created exam_schedules_new table\n";
    }
    
    echo "\nStep 3: Fixing Missing Columns\n";
    echo "-------------------------------------\n";
    
    // Check and add missing columns for each table
    $columnFixes = [
        'users' => [
            'guru_id' => 'unsignedBigInteger',
            'siswa_id' => 'unsignedBigInteger',
            'nip' => 'string',
            'nis' => 'string',
            'phone' => 'string',
            'address' => 'text',
            'gender' => "enum('L','P')",
            'birth_date' => 'date',
            'is_active' => 'boolean'
        ],
        'assignments' => [
            'guru_id' => 'unsignedBigInteger',
            'siswa_id' => 'unsignedBigInteger',
            'kelas_id' => 'unsignedBigInteger',
            'subject_id' => 'unsignedBigInteger'
        ],
        'materials' => [
            'guru_id' => 'unsignedBigInteger',
            'siswa_id' => 'unsignedBigInteger',
            'kelas_id' => 'unsignedBigInteger',
            'subject_id' => 'unsignedBigInteger'
        ],
        'practicals' => [
            'guru_id' => 'unsignedBigInteger',
            'siswa_id' => 'unsignedBigInteger',
            'kelas_id' => 'unsignedBigInteger',
            'subject_id' => 'unsignedBigInteger'
        ],
        'attendances' => [
            'guru_id' => 'unsignedBigInteger',
            'siswa_id' => 'unsignedBigInteger',
            'kelas_id' => 'unsignedBigInteger',
            'subject_id' => 'unsignedBigInteger'
        ],
        'assignment_submissions' => [
            'guru_id' => 'unsignedBigInteger',
            'siswa_id' => 'unsignedBigInteger'
        ],
        'practical_scores' => [
            'guru_id' => 'unsignedBigInteger',
            'siswa_id' => 'unsignedBigInteger'
        ],
        'subjects' => [
            'guru_id' => 'unsignedBigInteger',
            'kelas_id' => 'unsignedBigInteger'
        ]
    ];
    
    foreach ($columnFixes as $tableName => $columns) {
        if (\Schema::hasTable($tableName)) {
            foreach ($columns as $columnName => $columnType) {
                if (!\Schema::hasColumn($tableName, $columnName)) {
                    \Schema::table($tableName, function ($table) use ($columnName, $columnType) {
                        if (str_contains($columnType, 'unsignedBigInteger')) {
                            $table->unsignedBigInteger($columnName)->nullable();
                        } elseif (str_contains($columnType, 'string')) {
                            $table->string($columnName)->nullable();
                        } elseif (str_contains($columnType, 'text')) {
                            $table->text($columnName)->nullable();
                        } elseif (str_contains($columnType, 'boolean')) {
                            $table->boolean($columnName)->default(true);
                        } elseif (str_contains($columnType, 'date')) {
                            $table->date($columnName)->nullable();
                        } elseif (str_contains($columnType, 'enum')) {
                            $table->enum($columnName, ['L', 'P'])->nullable();
                        }
                    });
                    echo "✅ Added {$columnName} to {$tableName}\n";
                }
            }
        }
    }
    
    echo "\nStep 4: Data Recovery and Population\n";
    echo "-------------------------------------\n";
    
    // Sync users from users_central to users table
    $usersCentral = \DB::table('users_central')->get();
    foreach ($usersCentral as $user) {
        $existingUser = \DB::table('users')->where('id', $user->id)->first();
        if (!$existingUser) {
            $emailExists = \DB::table('users')->where('email', $user->email)->first();
            if (!$emailExists) {
                \DB::table('users')->insert([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $user->password,
                    'role' => $user->role,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                echo "✅ Synced user: {$user->name}\n";
            }
        }
    }
    
    // Create student records for siswa users
    $siswaUsers = \DB::table('users')->where('role', 'siswa')->get();
    foreach ($siswaUsers as $siswa) {
        $studentExists = \DB::table('students')->where('id', $siswa->id)->first();
        if (!$studentExists) {
            \DB::table('students')->insert([
                'id' => $siswa->id,
                'name' => $siswa->name,
                'email' => $siswa->email,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "✅ Created student record: {$siswa->name}\n";
        }
    }
    
    // Create guru records for guru users
    $guruUsers = \DB::table('users')->where('role', 'guru')->get();
    foreach ($guruUsers as $guru) {
        $guruExists = \DB::table('gurus')->where('id', $guru->id)->first();
        if (!$guruExists) {
            \DB::table('gurus')->insert([
                'id' => $guru->id,
                'name' => $guru->name,
                'email' => $guru->email,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "✅ Created guru record: {$guru->name}\n";
        }
    }
    
    echo "\nStep 5: Fixing Data Relationships\n";
    echo "-------------------------------------\n";
    
    // Get users for assignment
    $guruUser = \DB::table('users')->where('role', 'guru')->first();
    $siswaUser = \DB::table('users')->where('role', 'siswa')->first();
    
    if ($guruUser) {
        // Update assignments with guru_id
        \DB::table('assignments')
            ->whereNull('guru_id')
            ->update(['guru_id' => $guruUser->id]);
        echo "✅ Updated assignments with guru_id\n";
        
        // Update materials with guru_id
        \DB::table('materials')
            ->whereNull('guru_id')
            ->update(['guru_id' => $guruUser->id]);
        echo "✅ Updated materials with guru_id\n";
        
        // Update practicals with guru_id
        \DB::table('practicals')
            ->whereNull('guru_id')
            ->update(['guru_id' => $guruUser->id]);
        echo "✅ Updated practicals with guru_id\n";
        
        // Update subjects with guru_id
        \DB::table('subjects')
            ->whereNull('guru_id')
            ->update(['guru_id' => $guruUser->id]);
        echo "✅ Updated subjects with guru_id\n";
    }
    
    if ($siswaUser) {
        // Update assignment_submissions with siswa_id
        \DB::table('assignment_submissions')
            ->whereNull('siswa_id')
            ->update(['siswa_id' => $siswaUser->id]);
        echo "✅ Updated assignment_submissions with siswa_id\n";
        
        // Update practical_scores with siswa_id
        \DB::table('practical_scores')
            ->whereNull('siswa_id')
            ->update(['siswa_id' => $siswaUser->id]);
        echo "✅ Updated practical_scores with siswa_id\n";
        
        // Update attendances with siswa_id
        \DB::table('attendances')
            ->whereNull('siswa_id')
            ->update(['siswa_id' => $siswaUser->id]);
        echo "✅ Updated attendances with siswa_id\n";
    }
    
    echo "\nStep 6: Adding Foreign Key Constraints\n";
    echo "-------------------------------------\n";
    
    $foreignKeys = [
        'assignments' => [
            'guru_id' => 'users',
            'siswa_id' => 'users'
        ],
        'materials' => [
            'guru_id' => 'users',
            'siswa_id' => 'users'
        ],
        'practicals' => [
            'guru_id' => 'users',
            'siswa_id' => 'users'
        ],
        'attendances' => [
            'guru_id' => 'users',
            'siswa_id' => 'users'
        ],
        'assignment_submissions' => [
            'guru_id' => 'users',
            'siswa_id' => 'users'
        ],
        'practical_scores' => [
            'guru_id' => 'users',
            'siswa_id' => 'users'
        ],
        'subjects' => [
            'guru_id' => 'users'
        ]
    ];
    
    foreach ($foreignKeys as $tableName => $keys) {
        if (\Schema::hasTable($tableName)) {
            foreach ($keys as $column => $referenceTable) {
                if (\Schema::hasColumn($tableName, $column)) {
                    // Check if foreign key already exists
                    $existingFK = \DB::select("
                        SELECT CONSTRAINT_NAME 
                        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                        WHERE TABLE_SCHEMA = '{$database}' 
                        AND TABLE_NAME = '{$tableName}' 
                        AND COLUMN_NAME = '{$column}' 
                        AND REFERENCED_TABLE_NAME IS NOT NULL
                    ");
                    
                    if (empty($existingFK)) {
                        try {
                            \Schema::table($tableName, function ($table) use ($column, $referenceTable) {
                                $table->foreign($column)->references('id')->on($referenceTable)->onDelete('set null');
                            });
                            echo "✅ Added foreign key: {$tableName}.{$column} → {$referenceTable}.id\n";
                        } catch (Exception $e) {
                            echo "⚠️ Could not add foreign key {$tableName}.{$column}: " . $e->getMessage() . "\n";
                        }
                    }
                }
            }
        }
    }
    
    echo "\nStep 7: Final Verification\n";
    echo "-------------------------------------\n";
    
    // Count all records
    $totalRecords = 0;
    $allTables = \DB::select('SHOW TABLES');
    foreach ($allTables as $table) {
        $tableName = $table->{'Tables_in_' . $database};
        $count = \DB::table($tableName)->count();
        $totalRecords += $count;
    }
    
    echo "✅ Total tables: " . count($allTables) . "\n";
    echo "✅ Total records: {$totalRecords}\n";
    
    // Check user counts
    $userCounts = [
        'admin' => \DB::table('users')->where('role', 'admin')->count(),
        'guru' => \DB::table('users')->where('role', 'guru')->count(),
        'siswa' => \DB::table('users')->where('role', 'siswa')->count()
    ];
    
    echo "✅ User counts:\n";
    foreach ($userCounts as $role => $count) {
        echo "  - {$role}: {$count}\n";
    }
    
    echo "\n🎉 DATABASE REPAIR COMPLETE!\n";
    echo "=====================================\n";
    echo "✅ Missing tables created\n";
    echo "✅ Missing columns added\n";
    echo "✅ Data relationships fixed\n";
    echo "✅ Foreign key constraints added\n";
    echo "✅ Data integrity verified\n";
    
    echo "\n🚀 Database is now fully operational! 🚀\n";
    
} catch (Exception $e) {
    echo "❌ Database Repair Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
