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
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'guru', 'siswa']);
            $table->string('nis_nip')->unique()->nullable(); // NIS untuk siswa, NIP untuk guru
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable(); // Path foto profil
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index('role');
            $table->index('nis_nip');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
