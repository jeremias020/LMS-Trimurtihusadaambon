<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat tabel users_central jika belum ada
        if (!Schema::hasTable('users_central')) {
            Schema::create('users_central', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('username')->unique()->nullable();
                $table->enum('role', ['admin', 'guru', 'siswa'])->default('siswa');
                $table->string('phone')->nullable();
                $table->string('photo')->nullable();
                $table->boolean('is_active')->default(true);
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();

                $table->index('role');
                $table->index('is_active');
            });
        }

        // Buat tabel users (alias) jika belum ada, untuk backward compat
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('role')->default('siswa');
                $table->string('phone')->nullable();
                $table->boolean('is_active')->default(true);
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('users_central');
        Schema::dropIfExists('users');
    }
};
