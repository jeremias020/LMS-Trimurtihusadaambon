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
        Schema::create('exam_schedules_new', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('exam_type');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('kelas_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->string('location')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            $table->foreign('kelas_id')->references('id')->on('classes')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index('start_time');
            $table->index('is_published');
            $table->index('exam_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_schedules_new');
    }
};
