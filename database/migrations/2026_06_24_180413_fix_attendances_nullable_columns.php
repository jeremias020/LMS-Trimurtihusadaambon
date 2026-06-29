<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jadikan class_subject_id dan student_id nullable,
     * sehingga entri absensi manual (tanpa class_subject) tetap bisa disimpan.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Ubah kolom NOT NULL → nullable
            $table->unsignedBigInteger('class_subject_id')->nullable()->change();
            $table->unsignedBigInteger('student_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Isi dulu nilai NULL agar tidak gagal saat rollback ke NOT NULL
        DB::table('attendances')
            ->whereNull('class_subject_id')
            ->update(['class_subject_id' => 0]);

        DB::table('attendances')
            ->whereNull('student_id')
            ->update(['student_id' => 0]);

        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('class_subject_id')->nullable(false)->change();
            $table->unsignedBigInteger('student_id')->nullable(false)->change();
        });
    }
};
