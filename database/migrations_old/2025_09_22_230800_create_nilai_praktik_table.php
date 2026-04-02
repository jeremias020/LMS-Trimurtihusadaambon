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
        if (!Schema::hasTable('nilai_praktik')) {
            Schema::create('nilai_praktik', function (Blueprint $table) {
                $table->id();
                $table->foreignId('siswa_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
                $table->string('mata_praktik');
                $table->date('tanggal_praktik')->nullable();
                $table->decimal('total_nilai', 5, 2)->default(0);
                $table->string('grade', 2)->nullable();
                $table->text('feedback_otomatis')->nullable();
                $table->text('catatan_guru')->nullable();
                $table->enum('status', ['draft', 'final'])->default('draft');
                $table->timestamps();

                $table->index(['siswa_id']);
                $table->index(['guru_id']);
                $table->index(['mata_praktik']);
                $table->index(['status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_praktik');
    }
};
