<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('practical_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practical_id')->constrained('practicals')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained('criteria')->onDelete('cascade');
            $table->decimal('score', 5, 2);
            $table->text('feedback')->nullable();
            $table->boolean('laporan_generated')->default(false);
            $table->timestamp('laporan_generated_at')->nullable();
            $table->timestamps();

            $table->unique(['practical_id', 'siswa_id', 'criteria_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('practical_scores');
    }
};