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
        if (Schema::hasTable('siswa')) {
            return;
        }

        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('nis', 20)->unique();
            $table->string('nisn', 20)->unique()->nullable();
            $table->string('jenis_kelamin', 10); // L, P
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('no_telepon', 20);
            $table->unsignedBigInteger('kelas_id')->nullable();
            $table->string('major', 100)->nullable(); // Major name
            $table->string('tahun_ajaran', 20); // Academic year
            $table->string('nama_ortu', 100)->nullable();
            $table->string('no_telepon_ortu', 20)->nullable();
            $table->string('golongan_darah', 5)->nullable();
            $table->text('riwayat_penyakit')->nullable();
            $table->text('alergi')->nullable();
            $table->text('info_kesehatan')->nullable();
            $table->string('foto', 255)->nullable();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('kelas_id')->references('id')->on('classes')->onDelete('set null');
            
            // Indexes
            $table->index('nis');
            $table->index('nisn');
            $table->index('kelas_id');
            $table->index('status');
            $table->index('tahun_ajaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
