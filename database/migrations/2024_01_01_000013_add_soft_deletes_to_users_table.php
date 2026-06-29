<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pakai users_central (bukan users)
        foreach (['users_central', 'users'] as $tabel) {
            if (Schema::hasTable($tabel) && !Schema::hasColumn($tabel, 'deleted_at')) {
                Schema::table($tabel, function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['users_central', 'users'] as $tabel) {
            if (Schema::hasTable($tabel) && Schema::hasColumn($tabel, 'deleted_at')) {
                Schema::table($tabel, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }
};
