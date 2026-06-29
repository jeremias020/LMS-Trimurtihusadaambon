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
        if (Schema::hasTable('attendances')) {
            return;
        }

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_subject_id')->constrained('class_subjects')->onDelete('cascade'); // Absen per mata pelajaran
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['present', 'sick', 'permission', 'alpha']);
            $table->string('note')->nullable(); // Keterangan tambahan
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Guru yang input absen
            $table->timestamps();
            
            // Unique constraint untuk mencegah duplikasi absensi per siswa per pertemuan
            $table->unique(['class_subject_id', 'student_id', 'date'], 'att_unique_record');
            
            // Indexes
            $table->index('class_subject_id');
            $table->index('student_id');
            $table->index('date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
