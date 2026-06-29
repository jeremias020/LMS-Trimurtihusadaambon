<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Drop FK siswa_user_id_fk yang salah (referensi ke tabel 'users' lama),
     * biarkan tanpa FK sehingga siswa bisa dibuat untuk user di users_central.
     * Integritas dijaga di level aplikasi (Laravel).
     */
    public function up(): void
    {
        // Cek apakah FK masih ada sebelum drop
        $fkExists = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'siswa'
              AND CONSTRAINT_NAME = 'siswa_user_id_fk'
              AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ");

        if (!empty($fkExists)) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->dropForeign('siswa_user_id_fk');
            });
        }
    }

    public function down(): void
    {
        // Tidak perlu restore FK lama yang salah
    }
};
