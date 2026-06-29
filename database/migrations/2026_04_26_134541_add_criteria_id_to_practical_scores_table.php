<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('practical_scores', 'criteria_id')) {
            Schema::table('practical_scores', function (Blueprint $table) {
                $table->foreignId('criteria_id')->nullable()->after('siswa_id')->constrained()->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('practical_scores', 'criteria_id')) {
            Schema::table('practical_scores', function (Blueprint $table) {
                $table->dropForeign(['criteria_id']);
                $table->dropColumn('criteria_id');
            });
        }
    }
};
