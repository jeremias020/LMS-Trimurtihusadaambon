<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'pengirim_id')) {
                $table->unsignedBigInteger('pengirim_id')->nullable();
            }
            if (!Schema::hasColumn('notifications', 'sender_id')) {
                $table->unsignedBigInteger('sender_id')->nullable();
            }
            if (!Schema::hasColumn('notifications', 'receiver_id')) {
                $table->unsignedBigInteger('receiver_id')->nullable();
            }
            if (!Schema::hasColumn('notifications', 'receiver_type')) {
                $table->string('receiver_type')->nullable();
            }
            if (!Schema::hasColumn('notifications', 'tipe')) {
                $table->string('tipe')->default('info');
            }
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type')->nullable();
            }
            if (!Schema::hasColumn('notifications', 'judul')) {
                $table->string('judul')->nullable();
            }
            if (!Schema::hasColumn('notifications', 'pesan')) {
                $table->text('pesan')->nullable();
            }
            if (!Schema::hasColumn('notifications', 'url_aksi')) {
                $table->string('url_aksi')->nullable();
            }
            if (!Schema::hasColumn('notifications', 'prioritas')) {
                $table->string('prioritas')->default('sedang');
            }
            if (!Schema::hasColumn('notifications', 'priority')) {
                $table->string('priority')->nullable();
            }
            if (!Schema::hasColumn('notifications', 'status')) {
                $table->string('status')->default('belum_dibaca');
            }
            if (!Schema::hasColumn('notifications', 'scheduled_at')) {
                $table->timestamp('scheduled_at')->nullable();
            }
        });

        // Tambah index hanya jika belum ada
        $this->addIndexIfNotExists('notifications', ['receiver_id', 'receiver_type'], 'notifications_receiver_id_receiver_type_index');
        $this->addIndexIfNotExists('notifications', ['receiver_type'],  'notifications_receiver_type_index');
        $this->addIndexIfNotExists('notifications', ['tipe'],           'notifications_tipe_index');
        $this->addIndexIfNotExists('notifications', ['type'],           'notifications_type_index');
        $this->addIndexIfNotExists('notifications', ['status'],         'notifications_status_index');
        $this->addIndexIfNotExists('notifications', ['prioritas'],      'notifications_prioritas_index');
        $this->addIndexIfNotExists('notifications', ['priority'],       'notifications_priority_index');
        $this->addIndexIfNotExists('notifications', ['scheduled_at'],   'notifications_scheduled_at_index');
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $columns = [
                'pengirim_id', 'sender_id', 'receiver_id', 'receiver_type',
                'tipe', 'type', 'judul', 'pesan', 'url_aksi',
                'prioritas', 'priority', 'status', 'scheduled_at',
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('notifications', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    /**
     * Tambah index hanya jika belum ada di tabel.
     */
    private function addIndexIfNotExists(string $table, array $columns, string $indexName): void
    {
        try {
            $indexes = \DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
            if (empty($indexes)) {
                Schema::table($table, function (Blueprint $t) use ($columns, $indexName) {
                    $t->index($columns, $indexName);
                });
            }
        } catch (\Throwable $e) {
            // Abaikan jika index sudah ada atau terjadi error lain
        }
    }
};
