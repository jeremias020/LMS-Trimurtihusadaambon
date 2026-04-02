<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('practice_modules', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Kode Modul');
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->string('class')->comment('Kelas');
            $table->string('semester')->comment('Semester');
            $table->integer('credits')->default(0)->comment('SKS');
            $table->integer('duration')->default(0)->comment('Durasi dalam menit');
            $table->text('tools_required')->nullable()->comment('Alat yang diperlukan');
            $table->text('materials_required')->nullable()->comment('Bahan yang diperlukan');
            $table->text('safety_procedures')->nullable()->comment('Prosedur Keselamatan');
            $table->text('learning_objectives')->nullable()->comment('Tujuan Pembelajaran');
            $table->json('competency_indicators')->nullable()->comment('Indikator Kompetensi');
            $table->json('assessment_criteria')->nullable()->comment('Kriteria Penilaian');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['code']);
            $table->index(['subject_id']);
            $table->index(['class']);
            $table->index(['semester']);
            $table->index(['status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('practice_modules');
    }
};