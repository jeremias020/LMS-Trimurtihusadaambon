<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('practical_id')->nullable()->constrained('practicals')->onDelete('set null');
            $table->foreignId('practice_module_id')->nullable()->constrained('practice_modules')->onDelete('set null');
            $table->decimal('score', 5, 2)->default(0);
            $table->decimal('theory_score', 5, 2)->nullable();
            $table->decimal('practice_score', 5, 2)->nullable();
            $table->decimal('attitude_score', 5, 2)->nullable();
            $table->timestamp('scored_at')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['siswa_id', 'practice_module_id']);
            $table->index(['guru_id']);
            $table->index(['practical_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('scores');
    }
};