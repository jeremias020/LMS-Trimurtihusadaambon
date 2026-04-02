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
        // Add guru_id to materials table for direct relationship
        if (Schema::hasTable('materials') && !Schema::hasColumn('materials', 'guru_id')) {
            Schema::table('materials', function (Blueprint $table) {
                $table->foreignId('guru_id')->nullable()->after('class_subject_id')->comment('Direct teacher assignment');
                $table->index('guru_id');
            });
        }

        // Add guru_id to assignments table
        if (Schema::hasTable('assignments') && !Schema::hasColumn('assignments', 'guru_id')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->foreignId('guru_id')->nullable()->after('class_subject_id')->comment('Direct teacher assignment');
                $table->index('guru_id');
            });
        }

        // Add guru_id to practicals table
        if (Schema::hasTable('practicals') && !Schema::hasColumn('practicals', 'guru_id')) {
            Schema::table('practicals', function (Blueprint $table) {
                $table->foreignId('guru_id')->nullable()->after('class_subject_id')->comment('Direct teacher assignment');
                $table->index('guru_id');
            });
        }

        // Update existing records to populate guru_id from class_subjects
        if (Schema::hasColumn('materials', 'guru_id') && Schema::hasColumn('class_subjects', 'teacher_id')) {
            DB::statement('
                UPDATE materials m 
                JOIN class_subjects cs ON m.class_subject_id = cs.id 
                SET m.guru_id = cs.teacher_id 
                WHERE cs.teacher_id IS NOT NULL
            ');
        }
        
        if (Schema::hasColumn('assignments', 'guru_id') && Schema::hasColumn('class_subjects', 'teacher_id')) {
            DB::statement('
                UPDATE assignments a 
                JOIN class_subjects cs ON a.class_subject_id = cs.id 
                SET a.guru_id = cs.teacher_id 
                WHERE cs.teacher_id IS NOT NULL
            ');
        }
        
        if (Schema::hasColumn('practicals', 'guru_id') && Schema::hasColumn('class_subjects', 'teacher_id')) {
            DB::statement('
                UPDATE practicals p 
                JOIN class_subjects cs ON p.class_subject_id = cs.id 
                SET p.guru_id = cs.teacher_id 
                WHERE cs.teacher_id IS NOT NULL
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (Schema::hasColumn('materials', 'guru_id')) {
                $table->dropIndex(['guru_id']);
                $table->dropColumn('guru_id');
            }
        });

        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasColumn('assignments', 'guru_id')) {
                $table->dropIndex(['guru_id']);
                $table->dropColumn('guru_id');
            }
        });

        Schema::table('practicals', function (Blueprint $table) {
            if (Schema::hasColumn('practicals', 'guru_id')) {
                $table->dropIndex(['guru_id']);
                $table->dropColumn('guru_id');
            }
        });
    }
};
