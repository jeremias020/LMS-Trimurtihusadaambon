<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Temporarily disabled due to missing jurusan table
        // Schema::table('users', function (Blueprint $table) {
        //     if (!Schema::hasColumn('users', 'jurusan_id')) {
        //         $table->foreignId('jurusan_id')
        //               ->nullable()
        //               ->after('kelas_id')
        //               ->constrained('jurusan')
        //               ->nullOnDelete();
        //         $table->index('jurusan_id');
        //     }
        // });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'jurusan_id')) {
                $table->dropForeign(['jurusan_id']);
                $table->dropIndex(['jurusan_id']);
                $table->dropColumn('jurusan_id');
            }
        });
    }
};
