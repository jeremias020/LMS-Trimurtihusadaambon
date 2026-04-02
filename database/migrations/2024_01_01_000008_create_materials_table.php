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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_subject_id')->constrained('class_subjects')->onDelete('cascade'); // Materi ini milik pertemuan ke berapa/jadwal mana
            $table->string('title');
            $table->longText('content')->nullable(); // HTML Content
            $table->string('file_url')->nullable(); // Jika upload PDF/PPT
            $table->string('video_url')->nullable(); // Jika link Youtube
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('class_subject_id');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
