<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'type')) {
                $table->enum('type', ['regular', 'praktik'])->default('regular')->after('keterangan');
            }
            if (!Schema::hasColumn('attendances', 'practical_id')) {
                $table->foreignId('practical_id')->nullable()->after('type');
            }
            if (!Schema::hasColumn('attendances', 'recorded_by')) {
                $table->foreignId('recorded_by')->nullable()->after('practical_id');
            }
            if (!Schema::hasColumn('attendances', 'waktu_masuk')) {
                $table->time('waktu_masuk')->nullable()->after('tanggal');
            }
            if (!Schema::hasColumn('attendances', 'waktu_keluar')) {
                $table->time('waktu_keluar')->nullable()->after('waktu_masuk');
            }
        });

        // Add foreign keys in a separate statement, and only if not already present
        $fkPractical = 'attendances_practical_id_foreign';
        $fkRecordedBy = 'attendances_recorded_by_foreign';

        $hasFk = function (string $table, string $constraint) {
            $dbName = DB::getDatabaseName();
            $exists = DB::selectOne(
                "SELECT COUNT(*) AS cnt FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
                [$dbName, $table, $constraint]
            );
            return ($exists && (int)$exists->cnt > 0);
        };

        // Only add FK if column exists and FK not yet present
        Schema::table('attendances', function (Blueprint $table) use ($hasFk, $fkPractical, $fkRecordedBy) {
            if (Schema::hasColumn('attendances', 'practical_id') && !$hasFk('attendances', $fkPractical)) {
                $table->foreign('practical_id')->references('id')->on('practicals')->nullOnDelete();
            }
            if (Schema::hasColumn('attendances', 'recorded_by') && !$hasFk('attendances', $fkRecordedBy)) {
                $table->foreign('recorded_by')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // drop fks if exist
            try { $table->dropForeign(['practical_id']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['recorded_by']); } catch (\Throwable $e) {}

            if (Schema::hasColumn('attendances', 'waktu_keluar')) {
                $table->dropColumn('waktu_keluar');
            }
            if (Schema::hasColumn('attendances', 'waktu_masuk')) {
                $table->dropColumn('waktu_masuk');
            }
            if (Schema::hasColumn('attendances', 'recorded_by')) {
                $table->dropColumn('recorded_by');
            }
            if (Schema::hasColumn('attendances', 'practical_id')) {
                $table->dropColumn('practical_id');
            }
            if (Schema::hasColumn('attendances', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
