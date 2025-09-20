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
        Schema::create('practice_schedule_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_schedule_id')->constrained('practice_schedules')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['registered', 'present', 'absent', 'excused'])->default('registered');
            $table->timestamp('registered_at')->useCurrent();
            $table->text('notes')->nullable(); // Catatan untuk peserta
            $table->timestamps();
            
            // Unique constraint to prevent duplicate registration
            $table->unique(['practice_schedule_id', 'student_id'], 'schedule_student_unique');
            
            // Indexes
            $table->index(['practice_schedule_id', 'status']);
            $table->index(['student_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_schedule_participants');
    }
};
