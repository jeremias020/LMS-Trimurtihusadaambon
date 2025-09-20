<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->foreignId('guru_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            $table->integer('sks')->default(2);
            $table->enum('type', ['teori', 'praktikum', 'campuran'])->default('teori');
            $table->string('color')->nullable()->comment('Color code for UI purposes');
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['kelas_id']);
        });
    }

    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropIndex(['kelas_id']);
        });

        Schema::dropIfExists('subjects');
    }
};