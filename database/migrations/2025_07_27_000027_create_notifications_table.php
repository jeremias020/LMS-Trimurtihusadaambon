<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Pengirim dan penerima
            $table->foreignId('pengirim_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('penerima_id')->constrained('users')->onDelete('cascade');

            // Tipe notifikasi
            $table->enum('tipe_penerima', ['siswa', 'guru', 'admin', 'semua']);
            $table->enum('tipe', ['info', 'peringatan', 'sukses', 'error', 'sistem']);

            // Konten notifikasi
            $table->string('judul');
            $table->text('pesan');
            $table->string('url_aksi')->nullable();

            // Metadata
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi', 'darurat'])->default('sedang');
            $table->enum('status', ['belum_dibaca', 'terbaca', 'diarsipkan'])->default('belum_dibaca');

            // Timestamps
            $table->timestamp('read_at')->nullable()->comment('Waktu dibaca');
            $table->timestamp('scheduled_at')->nullable()->comment('Waktu terjadwal');
            $table->timestamps();

            // Indexes
            $table->index('penerima_id');
            $table->index('status');
            $table->index('prioritas');
            $table->index('read_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
