<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Temporarily disabled due to tablespace issues
        // Schema::create('kriteria_penilaian', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('nama');
        //     $table->enum('kategori', ['persiapan', 'pelaksanaan', 'hasil', 'sikap']);
        //     $table->decimal('bobot', 5, 2); // store as 0.00 .. 1.00
        //     $table->text('deskripsi')->nullable();
        //     $table->json('sop_checklist')->default('[]');
        //     $table->string('mata_praktik');
        //     $table->enum('tingkat_kelas', ['X', 'XI', 'XII']);
        //     $table->boolean('status')->default(true);
        //     $table->timestamps();
        //     $table->index(['mata_praktik', 'tingkat_kelas']);
        //     $table->index(['kategori']);
        // });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('kriteria_penilaian');
    }
};

