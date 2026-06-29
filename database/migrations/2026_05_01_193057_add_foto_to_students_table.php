<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel 'students' mungkin tidak ada — pakai 'siswa'
        $tabel = Schema::hasTable('students') ? 'students' : (Schema::hasTable('siswa') ? 'siswa' : null);
        if ($tabel && !Schema::hasColumn($tabel, 'foto')) {
            Schema::table($tabel, function (Blueprint $table) {
                $table->string('foto')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (['students', 'siswa'] as $tabel) {
            if (Schema::hasTable($tabel) && Schema::hasColumn($tabel, 'foto')) {
                Schema::table($tabel, function (Blueprint $table) {
                    $table->dropColumn('foto');
                });
            }
        }
    }
};
