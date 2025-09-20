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
        Schema::table('practicals', function (Blueprint $table) {
            // Tambah field yang hilang dari controller
            if (!Schema::hasColumn('practicals', 'waktu_mulai')) {
                $table->time('waktu_mulai')->nullable();
            }
            if (!Schema::hasColumn('practicals', 'waktu_selesai')) {
                $table->time('waktu_selesai')->nullable();
            }
            if (!Schema::hasColumn('practicals', 'skill_level')) {
                $table->enum('skill_level', ['Pemula', 'Menengah', 'Mahir'])->nullable();
            }
            if (!Schema::hasColumn('practicals', 'keselamatan')) {
                $table->text('keselamatan')->nullable();
            }
            if (!Schema::hasColumn('practicals', 'kelas')) {
                $table->string('kelas')->nullable();
            }
            if (!Schema::hasColumn('practicals', 'max_score')) {
                $table->integer('max_score')->default(100);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('practicals', function (Blueprint $table) {
            $table->dropColumn([
                'waktu_mulai',
                'waktu_selesai', 
                'skill_level',
                'keselamatan',
                'kelas',
                'max_score'
            ]);
        });
    }
};
