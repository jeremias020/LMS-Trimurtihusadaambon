<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('practical_scores')) {
            Schema::create('practical_scores', function (Blueprint $table) {
                $table->id();
                $table->foreignId('practical_id')->nullable()->comment('Practical work being scored');
                $table->foreignId('siswa_id')->nullable()->comment('Student who submitted');
                $table->foreignId('guru_id')->nullable()->comment('Teacher who scored');
                $table->foreignId('subject_id')->nullable()->comment('Subject of the practical');
                $table->decimal('score', 8, 2)->nullable()->comment('Score obtained (0.00 - 100.00)');
                $table->decimal('max_score', 8, 2)->default(100.00)->comment('Maximum possible score');
                $table->string('grade', 10)->nullable()->comment('Grade (A, B, C, D, E)');
                $table->text('feedback')->nullable()->comment('Teacher feedback');
                $table->text('notes')->nullable()->comment('Additional notes');
                $table->timestamp('submitted_at')->nullable()->comment('When the student submitted');
                $table->timestamp('scored_at')->nullable()->comment('When the teacher scored');
                $table->string('status')->default('submitted')->comment('Status: submitted, scored, returned');
                $table->string('file_path')->nullable()->comment('Path to submitted file');
                $table->string('file_name')->nullable()->comment('Original file name');
                $table->integer('file_size')->nullable()->comment('File size in bytes');
                $table->string('mime_type')->nullable()->comment('File MIME type');
                $table->json('evidence_files')->nullable()->comment('List of evidence files');
                $table->string('video_url')->nullable()->comment('Video evidence URL');
                $table->json('checklist_items')->nullable()->comment('SOP checklist completion');
                $table->boolean('is_late')->default(false)->comment('Whether submission was late');
                $table->timestamp('due_date')->nullable()->comment('Original due date');
                $table->integer('attempt')->default(1)->comment('Attempt number');
                $table->timestamps();
                $table->softDeletes();

                $table->index('practical_id');
                $table->index('siswa_id');
                $table->index('guru_id');
                $table->index('subject_id');
                $table->index('status');
                $table->index(['practical_id', 'siswa_id']);
            });
        }

        // Tambah scores_count ke practicals jika belum ada
        if (Schema::hasTable('practicals') && !Schema::hasColumn('practicals', 'scores_count')) {
            Schema::table('practicals', function (Blueprint $table) {
                $table->integer('scores_count')->default(0)->after('submissions_count');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('practical_scores');

        if (Schema::hasTable('practicals') && Schema::hasColumn('practicals', 'scores_count')) {
            Schema::table('practicals', function (Blueprint $table) {
                $table->dropColumn('scores_count');
            });
        }
    }
};
