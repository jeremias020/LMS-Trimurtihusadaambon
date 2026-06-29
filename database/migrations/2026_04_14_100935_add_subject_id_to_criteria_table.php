<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('criteria', function (Blueprint $table) {
            if (!Schema::hasColumn('criteria', 'subject_id')) {
                $table->foreignId('subject_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('criteria', 'weight')) {
                $table->decimal('weight', 5, 2)->default(1.00)->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('criteria', function (Blueprint $table) {
            if (Schema::hasColumn('criteria', 'subject_id')) {
                $table->dropForeign(['subject_id']);
                $table->dropColumn('subject_id');
            }
            if (Schema::hasColumn('criteria', 'weight')) {
                $table->dropColumn('weight');
            }
        });
    }
};
