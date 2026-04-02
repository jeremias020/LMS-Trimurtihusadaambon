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
        Schema::create('class_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade'); // Guru yang mengajar mapel ini di kelas ini
            $table->enum('day', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->nullable(); // Contoh: Lab Keperawatan 1
            $table->timestamps();
            
            // Unique constraint untuk mencegah duplikasi jadwal
            $table->unique(['class_id', 'subject_id', 'teacher_id', 'day', 'start_time'], 'cs_unique_schedule');
            
            // Indexes
            $table->index('class_id');
            $table->index('subject_id');
            $table->index('teacher_id');
            $table->index('day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_subjects');
    }
};
