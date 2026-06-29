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
        Schema::table('criteria', function (Blueprint $table) {
            $table->foreignId('subject_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->decimal('weight', 5, 2)->default(1.00)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('criteria', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn(['subject_id', 'weight']);
        });
    }
};
