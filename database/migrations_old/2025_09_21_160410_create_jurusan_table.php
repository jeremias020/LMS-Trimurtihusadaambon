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
        Schema::create('jurusan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode', 10);
            $table->text('deskripsi')->nullable();
            $table->json('mata_pelajaran')->nullable(); // List of mata pelajaran in this jurusan
            $table->unsignedInteger('kapasitas_total')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique('kode');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jurusan');
    }
};
