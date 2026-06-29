<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('attendances') || !Schema::hasColumn('attendances', 'status')) {
            return;
        }

        try {
            $col = DB::select("SHOW COLUMNS FROM `attendances` LIKE 'status'");
            $colType = $col[0]->Type ?? '';

            if (str_contains($colType, 'enum')) {
                // Drop enum column dan buat ulang sebagai varchar
                Schema::table('attendances', function (Blueprint $table) {
                    $table->dropColumn('status');
                });
                Schema::table('attendances', function (Blueprint $table) {
                    $table->string('status', 50)->default('hadir')->after('date');
                });
            } elseif (!str_contains($colType, 'varchar')) {
                Schema::table('attendances', function (Blueprint $table) {
                    $table->string('status', 50)->default('hadir')->change();
                });
            }
        } catch (\Throwable $e) {
            // Abaikan jika gagal — kolom status mungkin sudah benar
        }
    }

    public function down(): void
    {
        // Tidak rollback
    }
};
