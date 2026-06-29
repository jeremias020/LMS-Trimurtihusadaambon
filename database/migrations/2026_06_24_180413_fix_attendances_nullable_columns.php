<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('attendances')) return;

        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'class_subject_id')) {
                $table->unsignedBigInteger('class_subject_id')->nullable()->change();
            }
            if (Schema::hasColumn('attendances', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        // Tidak rollback — lebih aman biarkan nullable
    }
};
