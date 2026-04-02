<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Test database connection and schema
echo "=== LMS TRIMURTI HUSADA - DATABASE VERIFICATION ===\n\n";

try {
    // Check connection
    $connection = DB::connection();
    echo "✅ Database Connection: " . $connection->getDatabaseName() . "\n";
    
    // Check all tables exist
    $expectedTables = [
        'users', 'majors', 'classes', 'class_students', 
        'subjects', 'class_subjects', 'assessment_criteria',
        'materials', 'assignments', 'assignment_submissions',
        'attendances', 'practical_assessments'
    ];
    
    echo "\n📊 Table Verification:\n";
    foreach ($expectedTables as $table) {
        $exists = Schema::hasTable($table);
        $count = $exists ? DB::table($table)->count() : 0;
        echo sprintf("  %-25s: %s (%d records)\n", $table, $exists ? '✅' : '❌', $count);
    }
    
    // Test relationships
    echo "\n🔗 Relationship Tests:\n";
    
    // Test users by role
    $adminCount = DB::table('users')->where('role', 'admin')->count();
    $guruCount = DB::table('users')->where('role', 'guru')->count();
    $siswaCount = DB::table('users')->where('role', 'siswa')->count();
    
    echo "  Admin Users: $adminCount\n";
    echo "  Guru Users: $guruCount\n";
    echo "  Siswa Users: $siswaCount\n";
    
    // Test majors
    $majors = DB::table('majors')->get();
    echo "\n📚 Majors:\n";
    foreach ($majors as $major) {
        echo "  - {$major->name} ({$major->code})\n";
    }
    
    // Test class-subject relationships
    $classSubjects = DB::table('class_subjects')
        ->join('classes', 'class_subjects.class_id', '=', 'classes.id')
        ->join('subjects', 'class_subjects.subject_id', '=', 'subjects.id')
        ->join('users', 'class_subjects.teacher_id', '=', 'users.id')
        ->select('classes.name as class_name', 'subjects.name as subject_name', 'users.name as teacher_name', 'class_subjects.day', 'class_subjects.start_time')
        ->get();
    
    echo "\n📅 Class Schedule:\n";
    foreach ($classSubjects as $schedule) {
        echo "  {$schedule->class_name} - {$schedule->subject_name} ({$schedule->teacher_name}) - {$schedule->day} {$schedule->start_time}\n";
    }
    
    // Test assessment criteria
    $criteria = DB::table('assessment_criteria')
        ->join('subjects', 'assessment_criteria.subject_id', '=', 'subjects.id')
        ->select('subjects.name as subject_name', 'assessment_criteria.code', 'assessment_criteria.name', 'assessment_criteria.type')
        ->get();
    
    echo "\n📋 Assessment Criteria:\n";
    foreach ($criteria as $criterion) {
        echo "  [{$criterion->code}] {$criterion->subject_name} - {$criterion->name} ({$criterion->type})\n";
    }
    
    echo "\n✅ Database verification completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
