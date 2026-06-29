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
        if (Schema::hasTable('practical_assessments')) {
            return;
        }

        Schema::create('practical_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained('assessment_criteria')->onDelete('cascade'); // Menilai berdasarkan KD/Kriteria tertentu
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade'); // Penguji
            $table->decimal('score', 5, 2); // Nilai angka
            $table->date('assessment_date');
            $table->text('notes')->nullable(); // Catatan penguji (misal: "Teknik steril kurang baik")
            $table->string('evidence_url')->nullable(); // Opsional: Foto/Video saat siswa praktik
            $table->timestamps();
            
            // Indexes
            $table->index('student_id');
            $table->index('subject_id');
            $table->index('criteria_id');
            $table->index('teacher_id');
            $table->index('assessment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practical_assessments');
    }
};
