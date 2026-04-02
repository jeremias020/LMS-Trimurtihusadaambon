<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== CLEANING EXAM_SCHEDULES TABLESPACE ===\n\n";

try {
    // Drop any existing exam_schedules table
    DB::statement("DROP TABLE IF EXISTS exam_schedules");
    echo "✅ Dropped any existing exam_schedules table\n";
    
    echo "\n=== CLEANUP COMPLETED ===\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
