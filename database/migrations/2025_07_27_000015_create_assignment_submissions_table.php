<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('assignment_submissions')) {
            Schema::create('assignment_submissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
                $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
                $table->text('submission_text')->nullable();
                $table->string('file_path')->nullable();
                $table->decimal('score', 5, 2)->nullable();
                $table->text('feedback')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('graded_at')->nullable();
                $table->softDeletes();
                $table->timestamps();

                $table->unique(['assignment_id', 'siswa_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('assignment_submissions');
    }
};