<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('exams')) {
            Schema::create('exams', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->integer('duration')->comment('Duration in minutes');
                $table->dateTime('start_time');
                $table->dateTime('end_time');
                $table->integer('total_questions')->default(0);
                $table->integer('passing_score')->default(60);
                $table->integer('max_score')->default(100);
                $table->boolean('is_published')->default(false);
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('exams');
    }
};