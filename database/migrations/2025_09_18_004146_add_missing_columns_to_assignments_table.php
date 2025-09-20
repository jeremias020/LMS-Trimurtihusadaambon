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
        Schema::table('assignments', function (Blueprint $table) {
            $table->text('instructions')->nullable()->after('description');
            $table->string('class', 10)->nullable()->after('subject_id');
            $table->boolean('allow_late')->default(false)->after('is_published');
            $table->string('file_path')->nullable()->after('file');
            $table->integer('file_size')->nullable()->after('file_path');
            $table->string('file_type', 10)->nullable()->after('file_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn([
                'instructions',
                'class',
                'allow_late',
                'file_path',
                'file_size',
                'file_type'
            ]);
        });
    }
};
