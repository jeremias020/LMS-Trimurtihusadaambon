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
        if (Schema::hasTable('classes')) {
            return;
        }

        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: X Keperawatan 1
            $table->foreignId('major_id')->constrained('majors')->onDelete('cascade');
            $table->string('academic_year'); // Contoh: 2023/2024
            $table->string('wallpaper')->nullable(); // Opsional: Gambar background kelas
            $table->timestamps();
            
            // Indexes
            $table->index('major_id');
            $table->index('academic_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
