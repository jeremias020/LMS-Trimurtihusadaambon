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
        if (Schema::hasTable('subjects')) {
            return;
        }

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Anatomi Fisiologi, Farmakologi
            $table->string('code')->unique();
            $table->foreignId('major_id')->constrained('majors')->onDelete('cascade'); // Mapel spesifik jurusan
            $table->timestamps();
            
            // Indexes
            $table->index('code');
            $table->index('major_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
