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
        Schema::table('practicals', function (Blueprint $table) {
            // Add is_published column if it doesn't exist
            if (!Schema::hasColumn('practicals', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('published_at');
            }
            
            // Add is_active column if it doesn't exist
            if (!Schema::hasColumn('practicals', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_published');
            }
            
            // Add views_count column if it doesn't exist
            if (!Schema::hasColumn('practicals', 'views_count')) {
                $table->integer('views_count')->default(0)->after('is_active');
            }
            
            // Add submissions_count column if it doesn't exist
            if (!Schema::hasColumn('practicals', 'submissions_count')) {
                $table->integer('submissions_count')->default(0)->after('views_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('practicals', function (Blueprint $table) {
            $table->dropColumn(['is_published', 'is_active', 'views_count', 'submissions_count']);
        });
    }
};
