<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Data identitas siswa
            $table->string('nis')->unique()->comment('Nomor Induk Siswa');
            $table->string('nisn')->unique()->nullable()->comment('Nomor Induk Siswa Nasional');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat')->nullable();
            $table->string('no_telepon')->nullable();

            // Foreign key ke tabel kelas
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');

            // Data akademik
            $table->string('major')->comment('Jurusan');
            $table->string('tahun_ajaran');

            // Data orang tua
            $table->string('nama_ortu')->nullable();
            $table->string('no_telepon_ortu')->nullable();

            // Data kesehatan
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O'])->nullable();
            $table->text('riwayat_penyakit')->nullable();
            $table->text('alergi')->nullable();
            $table->text('info_kesehatan')->nullable();

            // Status
            $table->enum('status', ['aktif', 'lulus', 'pindah', 'dropout'])->default('aktif');

            // Soft deletes
            $table->softDeletes();

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('nis');
            $table->index('nisn');
            $table->index('kelas_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('siswa');
    }
};
