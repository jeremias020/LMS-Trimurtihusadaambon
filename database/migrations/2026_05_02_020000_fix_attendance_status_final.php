<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check current status column structure
        $columnType = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM attendances LIKE 'status'")[0]->Type ?? '';
        
        Schema::table('attendances', function (Blueprint $table) use ($columnType) {
            // If it's still enum, change to varchar
            if (strpos($columnType, 'enum') !== false) {
                $table->dropColumn('status');
                $table->string('status', 50)->default('hadir')->after('date');
            } elseif (strpos($columnType, 'varchar') === false) {
                // If it's not varchar, change it
                $table->string('status', 50)->default('hadir')->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir')->change();
        });
    }
};
