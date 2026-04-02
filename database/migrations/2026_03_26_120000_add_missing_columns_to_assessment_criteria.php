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
        Schema::table('assessment_criteria', function (Blueprint $table) {
            // Add missing columns for KriteriaPenilaian model
            if (!Schema::hasColumn('assessment_criteria', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('assessment_criteria', 'weight')) {
                $table->decimal('weight', 5, 2)->default(0.10);
            }
            if (!Schema::hasColumn('assessment_criteria', 'max_score')) {
                $table->integer('max_score')->default(100);
            }
            if (!Schema::hasColumn('assessment_criteria', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('assessment_criteria', 'code')) {
                $table->string('code')->nullable()->default(null);
            }
            if (!Schema::hasColumn('assessment_criteria', 'mata_praktik')) {
                $table->string('mata_praktik')->nullable();
            }
            if (!Schema::hasColumn('assessment_criteria', 'tingkat_kelas')) {
                $table->enum('tingkat_kelas', ['X', 'XI', 'XII'])->nullable();
            }
            if (!Schema::hasColumn('assessment_criteria', 'subject_id')) {
                $table->foreignId('subject_id')->nullable()->default(null);
            }
            if (!Schema::hasColumn('assessment_criteria', 'sop_checklist')) {
                $table->json('sop_checklist')->nullable();
            }
            
            // Add indexes
            if (!Schema::hasIndex('assessment_criteria', 'assessment_criteria_is_active_index')) {
                $table->index('is_active');
            }
            if (!Schema::hasIndex('assessment_criteria', 'assessment_criteria_type_index')) {
                $table->index('type');
            }
            if (!Schema::hasIndex('assessment_criteria', 'assessment_criteria_subject_id_index')) {
                $table->index('subject_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_criteria', function (Blueprint $table) {
            $columnsToDrop = ['description', 'weight', 'max_score', 'is_active', 'mata_praktik', 'tingkat_kelas', 'sop_checklist'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('assessment_criteria', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
