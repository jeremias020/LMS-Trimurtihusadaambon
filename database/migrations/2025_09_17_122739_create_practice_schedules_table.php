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
        Schema::create('practice_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practical_id')->constrained('practicals')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade'); // Guru pengajar
            $table->string('title'); // Judul jadwal praktik
            $table->text('description')->nullable(); // Deskripsi praktik
            $table->date('practice_date'); // Tanggal praktik
            $table->time('start_time'); // Jam mulai
            $table->time('end_time'); // Jam selesai
            $table->string('location')->nullable(); // Lokasi praktik
            $table->integer('max_participants')->default(30); // Maksimal peserta
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->boolean('notification_sent')->default(false); // Status notifikasi sudah dikirim
            $table->timestamp('notification_sent_at')->nullable();
            $table->json('materials_needed')->nullable(); // Materi yang dibutuhkan (JSON)
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();
            
            // Indexes
            $table->index(['practice_date', 'status']);
            $table->index(['teacher_id', 'practice_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_schedules');
    }
};
