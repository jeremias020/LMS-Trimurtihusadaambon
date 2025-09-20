<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('material_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('downloaded_at')->useCurrent();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->unique(['material_id', 'siswa_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('material_downloads');
    }
};