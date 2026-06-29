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
        Schema::table('materials', function (Blueprint $table) {
            // Make class_subject_id nullable or add default
            if (Schema::hasColumn('materials', 'class_subject_id')) {
                $table->unsignedBigInteger('class_subject_id')->nullable()->change();
            }
        });

        Schema::table('assignments', function (Blueprint $table) {
            // Make class_subject_id nullable or add default
            if (Schema::hasColumn('assignments', 'class_subject_id')) {
                $table->unsignedBigInteger('class_subject_id')->nullable()->change();
            }
        });

        Schema::table('attendances', function (Blueprint $table) {
            // Make created_by nullable or add default
            if (Schema::hasColumn('attendances', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (Schema::hasColumn('materials', 'class_subject_id')) {
                $table->unsignedBigInteger('class_subject_id')->nullable(false)->change();
            }
        });

        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasColumn('assignments', 'class_subject_id')) {
                $table->unsignedBigInteger('class_subject_id')->nullable(false)->change();
            }
        });

        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable(false)->change();
            }
        });
    }
};
