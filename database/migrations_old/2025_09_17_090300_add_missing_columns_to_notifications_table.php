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
        Schema::table('notifications', function (Blueprint $table) {
            // Add Indonesian column names for compatibility
            if (!Schema::hasColumn('notifications', 'penerima_id')) {
                $table->foreignId('penerima_id')->nullable()->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('notifications', 'pengirim_id')) {
                $table->foreignId('pengirim_id')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('notifications', 'tipe_penerima')) {
                $table->enum('tipe_penerima', ['siswa', 'guru', 'admin', 'semua'])->default('semua');
            }
            if (!Schema::hasColumn('notifications', 'tipe')) {
                $table->enum('tipe', ['info', 'peringatan', 'sukses', 'error', 'sistem'])->default('info');
            }
            if (!Schema::hasColumn('notifications', 'judul')) {
                $table->string('judul')->default('Notification');
            }
            if (!Schema::hasColumn('notifications', 'pesan')) {
                $table->text('pesan')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'penerima_id')) {
                $table->dropForeign(['penerima_id']);
                $table->dropColumn('penerima_id');
            }
            if (Schema::hasColumn('notifications', 'pengirim_id')) {
                $table->dropForeign(['pengirim_id']);
                $table->dropColumn('pengirim_id');
            }
            if (Schema::hasColumn('notifications', 'tipe_penerima')) {
                $table->dropColumn('tipe_penerima');
            }
            if (Schema::hasColumn('notifications', 'tipe')) {
                $table->dropColumn('tipe');
            }
            if (Schema::hasColumn('notifications', 'judul')) {
                $table->dropColumn('judul');
            }
            if (Schema::hasColumn('notifications', 'pesan')) {
                $table->dropColumn('pesan');
            }
        });
    }
};
