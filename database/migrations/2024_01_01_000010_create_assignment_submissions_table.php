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
        if (Schema::hasTable('assignment_submissions')) {
            return;
        }

        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('file_url')->nullable(); // File jawaban siswa
            $table->text('submission_text')->nullable(); // Jawaban teks
            $table->timestamp('submitted_at')->nullable();
            $table->integer('score')->nullable(); // Nilai yang diberikan guru
            $table->text('feedback')->nullable(); // Komentar guru
            $table->enum('status', ['submitted', 'graded', 'late'])->default('submitted');
            $table->timestamps();
            
            // Unique constraint untuk mencegah duplikasi pengumpulan
            $table->unique(['assignment_id', 'student_id'], 'as_unique_submission');
            
            // Indexes
            $table->index('assignment_id');
            $table->index('student_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
