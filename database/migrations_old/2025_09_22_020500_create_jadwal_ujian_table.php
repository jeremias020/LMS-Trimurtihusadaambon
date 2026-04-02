<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Temporarily disabled due to tablespace issues
        // Schema::create('jadwal_ujian', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('nama');
        //     $table->string('mata_pelajaran');
        //     $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
        //     $table->foreignId('jurusan_id')->nullable()->constrained('jurusan')->onDelete('set null');
        //     $table->foreignId('pengawas_id')->nullable()->constrained('users')->onDelete('set null');
        //     $table->date('tanggal');
        //     $table->time('waktu_mulai');
        //     $table->time('waktu_selesai')->nullable();
        //     $table->unsignedSmallInteger('durasi_menit')->nullable();
        //     $table->enum('tipe', ['quiz', 'uts', 'uas', 'praktik'])->default('quiz');
        //     $table->string('lokasi')->nullable();
        //     $table->text('deskripsi')->nullable();
        //     $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
        //     $table->timestamps();
        //     $table->index(['tanggal']);
        //     $table->index(['kelas_id', 'jurusan_id']);
        // });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('jadwal_ujian');
    }
};

