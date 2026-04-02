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
        Schema::table('classes', function (Blueprint $table) {
            // Add grade column if it doesn't exist
            if (!Schema::hasColumn('classes', 'grade')) {
                $table->string('grade')->after('name');
                $table->index('grade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            if (Schema::hasColumn('classes', 'grade')) {
                $table->dropIndex(['grade']);
                $table->dropColumn('grade');
            }
        });
    }
};
