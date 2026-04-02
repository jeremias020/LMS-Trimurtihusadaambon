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
            // Add missing columns
            if (!Schema::hasColumn('notifications', 'sender_id')) {
                $table->unsignedBigInteger('sender_id')->nullable()->after('pengirim_id');
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('notifications', 'receiver_id')) {
                $table->unsignedBigInteger('receiver_id')->nullable()->after('penerima_id');
                $table->foreign('receiver_id')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('notifications', 'receiver_type')) {
                $table->string('receiver_type')->nullable()->after('tipe_penerima');
            }
            
            if (!Schema::hasColumn('notifications', 'tipe')) {
                $table->string('tipe')->default('info')->after('receiver_type');
            }
            
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type')->nullable()->after('tipe');
            }
            
            if (!Schema::hasColumn('notifications', 'judul')) {
                $table->string('judul')->nullable()->after('type');
            }
            
            if (!Schema::hasColumn('notifications', 'pesan')) {
                $table->text('pesan')->nullable()->after('judul');
            }
            
            if (!Schema::hasColumn('notifications', 'url_aksi')) {
                $table->string('url_aksi')->nullable()->after('pesan');
            }
            
            if (!Schema::hasColumn('notifications', 'prioritas')) {
                $table->string('prioritas')->default('sedang')->after('url_aksi');
            }
            
            if (!Schema::hasColumn('notifications', 'priority')) {
                $table->string('priority')->nullable()->after('prioritas');
            }
            
            if (!Schema::hasColumn('notifications', 'status')) {
                $table->string('status')->default('belum_dibaca')->after('priority');
            }
            
            if (!Schema::hasColumn('notifications', 'scheduled_at')) {
                $table->timestamp('scheduled_at')->nullable()->after('read_at');
            }
            
            // Add indexes
            $table->index(['receiver_id', 'receiver_type']);
            $table->index('receiver_type');
            $table->index('tipe');
            $table->index('type');
            $table->index('status');
            $table->index('prioritas');
            $table->index('priority');
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Drop added columns
            $columns = [
                'sender_id', 'receiver_id', 'receiver_type',
                'tipe', 'type', 'judul', 'pesan', 'url_aksi',
                'prioritas', 'priority', 'status', 'scheduled_at'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('notifications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
