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
        Schema::table('practicals', function (Blueprint $table) {
            // Hapus kolom kelas string
            if (Schema::hasColumn('practicals', 'kelas')) {
                $table->dropColumn('kelas');
            }
            
            // Tambah kolom kelas_id dengan foreign key
            if (!Schema::hasColumn('practicals', 'kelas_id')) {
                $table->foreignId('kelas_id')->nullable()->after('keselamatan')
                      ->constrained('kelas')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('practicals', function (Blueprint $table) {
            // Kembalikan ke struktur sebelumnya
            if (Schema::hasColumn('practicals', 'kelas_id')) {
                $table->dropForeign(['kelas_id']);
                $table->dropColumn('kelas_id');
            }
            
            if (!Schema::hasColumn('practicals', 'kelas')) {
                $table->string('kelas')->nullable();
            }
        });
    }
};
