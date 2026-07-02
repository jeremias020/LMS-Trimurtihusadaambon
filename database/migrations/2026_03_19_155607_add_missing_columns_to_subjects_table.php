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
        Schema::table('subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('subjects', 'description')) {
                $table->string('description')->nullable();
            }
            if (!Schema::hasColumn('subjects', 'guru_id')) {
                $table->foreignId('guru_id')->nullable()->constrained('users_central')->onDelete('set null');
            }
            if (!Schema::hasColumn('subjects', 'kelas_id')) {
                $table->foreignId('kelas_id')->nullable()->constrained('classes')->onDelete('set null');
            }
            if (!Schema::hasColumn('subjects', 'sks')) {
                $table->integer('sks')->default(1);
            }
            if (!Schema::hasColumn('subjects', 'type')) {
                $table->enum('type', ['teori', 'praktikum', 'campuran'])->default('teori');
            }
            if (!Schema::hasColumn('subjects', 'color')) {
                $table->string('color')->nullable();
            }
            if (!Schema::hasColumn('subjects', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('subjects', 'order')) {
                $table->integer('order')->default(0);
            }
            
            // Indexes
            if (!Schema::hasIndex('subjects', 'subjects_type_index')) {
                $table->index('type');
            }
            if (!Schema::hasIndex('subjects', 'subjects_is_active_index')) {
                $table->index('is_active');
            }
            if (!Schema::hasIndex('subjects', 'subjects_guru_id_index')) {
                $table->index('guru_id');
            }
            if (!Schema::hasIndex('subjects', 'subjects_kelas_id_index')) {
                $table->index('kelas_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Drop indexes first
            if (Schema::hasIndex('subjects', 'subjects_type_index')) {
                $table->dropIndex(['type']);
            }
            if (Schema::hasIndex('subjects', 'subjects_is_active_index')) {
                $table->dropIndex(['is_active']);
            }
            if (Schema::hasIndex('subjects', 'subjects_guru_id_index')) {
                $table->dropIndex(['guru_id']);
            }
            if (Schema::hasIndex('subjects', 'subjects_kelas_id_index')) {
                $table->dropIndex(['kelas_id']);
            }
            
            // Drop columns
            $columnsToDrop = ['description', 'guru_id', 'kelas_id', 'sks', 'type', 'color', 'is_active', 'order'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('subjects', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
