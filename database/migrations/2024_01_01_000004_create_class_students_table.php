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
        if (Schema::hasTable('class_students')) {
            return;
        }

        Schema::create('class_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade'); // Hanya user dengan role 'siswa'
            $table->timestamps();
            
            // Unique constraint untuk mencegah duplikasi siswa di kelas yang sama
            $table->unique(['class_id', 'student_id'], 'cs_unique_student');
            
            // Indexes
            $table->index('class_id');
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_students');
    }
};
