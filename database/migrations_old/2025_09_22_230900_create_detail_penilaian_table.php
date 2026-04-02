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
        if (!Schema::hasTable('detail_penilaian')) {
            Schema::create('detail_penilaian', function (Blueprint $table) {
                $table->id();
                $table->foreignId('nilai_praktik_id')->constrained('nilai_praktik')->cascadeOnDelete();
                $table->foreignId('kriteria_id')->constrained('kriteria_penilaian')->cascadeOnDelete();
                $table->unsignedTinyInteger('skor')->default(0); // 0..4
                $table->text('catatan')->nullable();
                $table->timestamps();

                $table->index(['nilai_praktik_id']);
                $table->index(['kriteria_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penilaian');
    }
};
