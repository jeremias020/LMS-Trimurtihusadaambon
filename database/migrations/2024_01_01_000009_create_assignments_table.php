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
        if (Schema::hasTable('assignments')) {
            return;
        }

        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_subject_id')->constrained('class_subjects')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_url')->nullable(); // Soal tugas (PDF)
            $table->dateTime('due_date'); // Deadline
            $table->integer('max_score')->default(100);
            $table->timestamps();
            
            // Indexes
            $table->index('class_subject_id');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
