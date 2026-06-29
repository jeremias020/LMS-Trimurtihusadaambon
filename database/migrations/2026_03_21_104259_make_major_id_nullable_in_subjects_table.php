<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('subjects') && Schema::hasColumn('subjects', 'major_id')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->unsignedBigInteger('major_id')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        // Tidak perlu balik ke NOT NULL
    }
};
