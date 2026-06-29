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
        Schema::table('attendances', function (Blueprint $table) {
            // Check if status column exists and fix it
            if (Schema::hasColumn('attendances', 'status')) {
                // Change to string to allow longer status values
                $table->string('status', 50)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'status')) {
                $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->change();
            }
        });
    }
};
