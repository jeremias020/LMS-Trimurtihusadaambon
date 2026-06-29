<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_downloads', function (Blueprint $table) {
            if (!Schema::hasColumn('material_downloads', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('downloaded_at');
            }
            if (!Schema::hasColumn('material_downloads', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('material_downloads', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'user_agent']);
        });
    }
};
