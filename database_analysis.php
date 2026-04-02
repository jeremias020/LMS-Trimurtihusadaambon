<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🗄️ LMS TRIMURTI DATABASE COMPREHENSIVE ANALYSIS\n";
echo "===============================================\n\n";

try {
    echo "📊 DATABASE OVERVIEW:\n";
    echo "Database Name: " . \DB::connection()->getDatabaseName() . "\n";
    echo "Database Connection: " . \DB::connection()->getConfig('driver') . "\n\n";

    echo "📋 TABLES LIST & STATUS:\n";
    echo "=====================================\n";
    
    $tables = \DB::select('SHOW TABLES');
    $database = \DB::connection()->getDatabaseName();
    
    foreach ($tables as $table) {
        $tableName = $table->{'Tables_in_' . $database};
        $columns = \DB::select("SHOW COLUMNS FROM `{$tableName}`");
        $recordCount = \DB::table($tableName)->count();
        
        echo "📁 {$tableName} ({$recordCount} records)\n";
        
        // Check for important columns
        $importantColumns = ['id', 'created_at', 'updated_at', 'deleted_at'];
        foreach ($importantColumns as $col) {
            $hasColumn = collect($columns)->firstWhere('Field', $col);
            if ($hasColumn) {
                echo "  ✅ {$col}: " . $hasColumn->Type . "\n";
            } else {
                echo "  ❌ {$col}: Missing\n";
            }
        }
        
        // Check for foreign keys
        $foreignKeys = \DB::select("SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = '{$database}' AND TABLE_NAME = '{$tableName}' AND REFERENCED_TABLE_NAME IS NOT NULL");
        if (!empty($foreignKeys)) {
            echo "  🔗 Foreign Keys:\n";
            foreach ($foreignKeys as $fk) {
                echo "    - {$fk->COLUMN_NAME} → {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}\n";
            }
        }
        
        echo "\n";
    }
    
    echo "🔗 CRITICAL RELATIONSHIPS VERIFICATION:\n";
    echo "=====================================\n";
    
    // Test User relationships
    echo "👤 User Model Relationships:\n";
    try {
        $users = \DB::table('users')->get();
        echo "  - Total Users: " . count($users) . "\n";
        
        $adminCount = \DB::table('users')->where('role', 'admin')->count();
        $guruCount = \DB::table('users')->where('role', 'guru')->count();
        $siswaCount = \DB::table('users')->where('role', 'siswa')->count();
        
        echo "  - Admin Users: {$adminCount}\n";
        echo "  - Guru Users: {$guruCount}\n";
        echo "  - Siswa Users: {$siswaCount}\n";
        
    } catch (Exception $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n";
    }
    
    // Test Students data
    echo "\n🎓 Student Data Verification:\n";
    try {
        $students = \DB::table('users')->where('role', 'siswa')->get();
        foreach ($students as $student) {
            echo "  - {$student->name} (ID: {$student->id})\n";
            
            // Check class assignment
            $classStudent = \DB::table('class_students')->where('student_id', $student->id)->first();
            if ($classStudent) {
                $kelas = \DB::table('classes')->where('id', $classStudent->class_id)->first();
                echo "    → Class: " . ($kelas ? $kelas->name : 'Unknown') . "\n";
            } else {
                echo "    → Class: Not assigned\n";
            }
        }
    } catch (Exception $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n";
    }
    
    // Test Guru data
    echo "\n👨‍🏫 Guru Data Verification:\n";
    try {
        $gurus = \DB::table('users')->where('role', 'guru')->get();
        foreach ($gurus as $guru) {
            echo "  - {$guru->name} (ID: {$guru->id})\n";
            
            // Check assigned subjects
            $subjects = \DB::table('subjects')->where('guru_id', $guru->id)->get();
            echo "    → Subjects: " . count($subjects) . "\n";
            
            // Check assigned practicals
            $practicals = \DB::table('practicals')->where('guru_id', $guru->id)->get();
            echo "    → Practicals: " . count($practicals) . "\n";
        }
    } catch (Exception $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n";
    }
    
    // Test Class data
    echo "\n🏫 Class Data Verification:\n";
    try {
        $classes = \DB::table('classes')->get();
        foreach ($classes as $class) {
            echo "  - {$class->name} (ID: {$class->id})\n";
            
            // Check jurusan
            if ($class->jurusan_id) {
                $jurusan = \DB::table('jurusans')->where('id', $class->jurusan_id)->first();
                echo "    → Jurusan: " . ($jurusan ? $jurusan->name : 'Unknown') . "\n";
            }
            
            // Check student count
            $studentCount = \DB::table('class_students')->where('class_id', $class->id)->count();
            echo "    → Students: {$studentCount}\n";
            
            // Check subject count
            $subjectCount = \DB::table('subjects')->where('kelas_id', $class->id)->count();
            echo "    → Subjects: {$subjectCount}\n";
        }
    } catch (Exception $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n";
    }
    
    // Test Subject data
    echo "\n📚 Subject Data Verification:\n";
    try {
        $subjects = \DB::table('subjects')->get();
        foreach ($subjects as $subject) {
            echo "  - {$subject->name} (ID: {$subject->id})\n";
            
            // Check guru assignment
            if ($subject->guru_id) {
                $guru = \DB::table('users')->where('id', $subject->guru_id)->first();
                echo "    → Guru: " . ($guru ? $guru->name : 'Unknown') . "\n";
            }
            
            // Check class assignment
            if ($subject->kelas_id) {
                $kelas = \DB::table('classes')->where('id', $subject->kelas_id)->first();
                echo "    → Class: " . ($kelas ? $kelas->name : 'Unknown') . "\n";
            }
        }
    } catch (Exception $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n";
    }
    
    // Test Assignment data
    echo "\n📝 Assignment Data Verification:\n";
    try {
        $assignments = \DB::table('assignments')->get();
        echo "  - Total Assignments: " . count($assignments) . "\n";
        
        $submittedCount = \DB::table('assignment_submissions')->count();
        echo "  - Total Submissions: {$submittedCount}\n";
        
        $gradedCount = \DB::table('assignment_submissions')->whereNotNull('score')->count();
        echo "  - Graded Submissions: {$gradedCount}\n";
        
    } catch (Exception $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n";
    }
    
    // Test Practical data
    echo "\n🔬 Practical Data Verification:\n";
    try {
        $practicals = \DB::table('practicals')->get();
        echo "  - Total Practicals: " . count($practicals) . "\n";
        
        $scoredCount = \DB::table('practical_scores')->count();
        echo "  - Total Practical Scores: {$scoredCount}\n";
        
    } catch (Exception $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n";
    }
    
    // Test Attendance data
    echo "\n📋 Attendance Data Verification:\n";
    try {
        $attendances = \DB::table('attendances')->get();
        echo "  - Total Attendance Records: " . count($attendances) . "\n";
        
        $presentCount = \DB::table('attendances')->where('status', 'hadir')->count();
        echo "  - Present Records: {$presentCount}\n";
        
    } catch (Exception $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n";
    }
    
    // Test Material data
    echo "\n📚 Material Data Verification:\n";
    try {
        $materials = \DB::table('materials')->get();
        echo "  - Total Materials: " . count($materials) . "\n";
        
        foreach ($materials as $material) {
            echo "  - {$material->title}\n";
            if ($material->guru_id) {
                $guru = \DB::table('users')->where('id', $material->guru_id)->first();
                echo "    → Guru: " . ($guru ? $guru->name : 'Unknown') . "\n";
            }
        }
    } catch (Exception $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n🔍 DATA INTEGRITY CHECKS:\n";
    echo "=====================================\n";
    
    // Check for orphaned records
    echo "🔍 Orphaned Records Check:\n";
    
    // Check class_students with invalid student_id
    $orphanedClassStudents = \DB::table('class_students')
        ->leftJoin('users', 'class_students.student_id', '=', 'users.id')
        ->whereNull('users.id')
        ->count();
    echo "  - Orphaned class_students: {$orphanedClassStudents}\n";
    
    // Check subjects with invalid guru_id
    $orphanedSubjects = \DB::table('subjects')
        ->leftJoin('users', 'subjects.guru_id', '=', 'users.id')
        ->whereNull('users.id')
        ->whereNotNull('subjects.guru_id')
        ->count();
    echo "  - Orphaned subjects (guru): {$orphanedSubjects}\n";
    
    // Check assignments with invalid guru_id
    $orphanedAssignments = \DB::table('assignments')
        ->leftJoin('users', 'assignments.guru_id', '=', 'users.id')
        ->whereNull('users.id')
        ->whereNotNull('assignments.guru_id')
        ->count();
    echo "  - Orphaned assignments (guru): {$orphanedAssignments}\n";
    
    echo "\n📊 DATABASE STATISTICS:\n";
    echo "=====================================\n";
    
    $totalRecords = 0;
    foreach ($tables as $table) {
        $tableName = $table->{'Tables_in_' . $database};
        $count = \DB::table($tableName)->count();
        $totalRecords += $count;
        echo "  - {$tableName}: {$count} records\n";
    }
    
    echo "  - TOTAL RECORDS: {$totalRecords}\n";
    
    echo "\n🔐 SECURITY CHECKS:\n";
    echo "=====================================\n";
    
    // Check for sensitive data exposure
    echo "🔒 Security Analysis:\n";
    
    // Check if passwords are hashed
    $usersWithPlainPassword = \DB::table('users')
        ->where('password', 'NOT LIKE', '$%')
        ->count();
    echo "  - Users with plain passwords: {$usersWithPlainPassword}\n";
    
    // Check for active sessions
    echo "  - Session table exists: " . (\Schema::hasTable('sessions') ? 'Yes' : 'No') . "\n";
    
    // Check for proper indexing
    echo "  - Indexed tables: ";
    $indexedTables = [];
    foreach ($tables as $table) {
        $tableName = $table->{'Tables_in_' . $database};
        $indexes = \DB::select("SHOW INDEX FROM `{$tableName}`");
        if (!empty($indexes)) {
            $indexedTables[] = $tableName;
        }
    }
    echo count($indexedTables) . " out of " . count($tables) . "\n";
    
    echo "\n🚀 PERFORMANCE ANALYSIS:\n";
    echo "=====================================\n";
    
    echo "📈 Performance Metrics:\n";
    
    // Check table sizes
    echo "  - Table sizes:\n";
    foreach ($tables as $table) {
        $tableName = $table->{'Tables_in_' . $database};
        $size = \DB::select("SELECT ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'size_mb' FROM information_schema.TABLES WHERE table_schema = '{$database}' AND table_name = '{$tableName}'");
        if (!empty($size)) {
            echo "    - {$tableName}: {$size[0]->size_mb} MB\n";
        }
    }
    
    echo "\n🎯 DATABASE HEALTH SCORE:\n";
    echo "=====================================\n";
    
    $healthScore = 100;
    $issues = [];
    
    if ($usersWithPlainPassword > 0) {
        $healthScore -= 20;
        $issues[] = "Plain passwords found";
    }
    
    if ($orphanedClassStudents > 0) {
        $healthScore -= 10;
        $issues[] = "Orphaned class_students records";
    }
    
    if ($orphanedSubjects > 0) {
        $healthScore -= 10;
        $issues[] = "Orphaned subjects records";
    }
    
    if ($orphanedAssignments > 0) {
        $healthScore -= 10;
        $issues[] = "Orphaned assignments records";
    }
    
    echo "  - Health Score: {$healthScore}/100\n";
    
    if (!empty($issues)) {
        echo "  - Issues Found:\n";
        foreach ($issues as $issue) {
            echo "    • {$issue}\n";
        }
    }
    
    echo "\n🎉 DATABASE ANALYSIS COMPLETE!\n";
    echo "=====================================\n";
    echo "Status: " . ($healthScore >= 80 ? 'HEALTHY ✅' : ($healthScore >= 60 ? 'NEEDS ATTENTION ⚠️' : 'CRITICAL ❌')) . "\n";
    echo "Health Score: {$healthScore}/100\n";
    echo "Total Tables: " . count($tables) . "\n";
    echo "Total Records: {$totalRecords}\n";
    
    if ($healthScore >= 80) {
        echo "\n🌟 Database is in excellent condition!\n";
        echo "All critical systems are operational.\n";
    } elseif ($healthScore >= 60) {
        echo "\n⚠️ Database needs attention.\n";
        echo "Some issues found that should be addressed.\n";
    } else {
        echo "\n❌ Database requires immediate attention!\n";
        echo "Critical issues found that need immediate fixing.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database Analysis Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
