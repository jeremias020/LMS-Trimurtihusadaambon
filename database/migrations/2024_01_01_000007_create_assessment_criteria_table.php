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
        if (Schema::hasTable('assessment_criteria')) {
            return;
        }

        Schema::create('assessment_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->string('code'); // Contoh: KD 3.1
            $table->string('name'); // Contoh: Memahami sistem peredaran darah
            $table->enum('type', ['knowledge', 'skill']); // Membedakan teori dan praktik
            $table->timestamps();
            
            // Unique constraint untuk kode per subject
            $table->unique(['subject_id', 'code'], 'ac_unique_code');
            
            // Indexes
            $table->index('subject_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_criteria');
    }
};
