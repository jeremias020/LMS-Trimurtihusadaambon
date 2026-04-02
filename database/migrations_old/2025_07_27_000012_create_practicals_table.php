<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('practicals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->string('judul');
            $table->text('deskripsi');
            $table->date('tanggal');
            $table->string('lokasi')->nullable();
            $table->integer('durasi')->comment('Duration in minutes');
            $table->text('tools')->nullable();
            $table->text('bahan')->nullable();
            $table->text('instruksi');
            $table->boolean('is_published')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('practicals');
    }
};