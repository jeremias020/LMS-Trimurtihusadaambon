<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('deadline');
            $table->integer('max_score')->default(100);
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->string('file')->nullable();
            $table->boolean('is_published')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['subject_id']);
        });
    }

    public function down()
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropIndex(['subject_id']);
        });

        Schema::dropIfExists('assignments');
    }
};