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
            // Only add columns that don't exist
            if (!\Schema::hasColumn('subjects', 'type')) {
                $table->enum('type', ['teori', 'praktikum', 'campuran'])->default('teori');
                $table->index('type');
            }
            
            if (!\Schema::hasColumn('subjects', 'is_active')) {
                $table->boolean('is_active')->default(true);
                $table->index('is_active');
            }
            
            if (!\Schema::hasColumn('subjects', 'sks')) {
                $table->integer('sks')->default(1);
            }
            
            if (!\Schema::hasColumn('subjects', 'color')) {
                $table->string('color')->nullable();
            }
            
            if (!\Schema::hasColumn('subjects', 'order')) {
                $table->integer('order')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            try { $table->dropIndex(['type']); } catch (\Throwable $e) {}
            try { $table->dropIndex(['is_active']); } catch (\Throwable $e) {}

            foreach (['type', 'is_active', 'sks', 'color', 'order'] as $col) {
                if (Schema::hasColumn('subjects', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
