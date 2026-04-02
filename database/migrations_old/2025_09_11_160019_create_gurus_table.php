<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Data guru
            $table->string('nip')->unique()->comment('Nomor Induk Pegawai');
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('mata_pelajaran');
            $table->string('pendidikan_terakhir');
            $table->string('foto')->nullable();
            $table->enum('status', ['aktif', 'pensiun', 'pindah'])->default('aktif');

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('nip');
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gurus');
    }
};
