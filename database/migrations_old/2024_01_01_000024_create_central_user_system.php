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
        // Create centralized users table for authentication
        Schema::create('users_central', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('username')->unique();
            $table->enum('role', ['admin', 'guru', 'siswa']);
            $table->string('phone', 20)->nullable();
            $table->string('photo', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('email');
            $table->index('username');
            $table->index('role');
            $table->index('is_active');
        });

        // Update guru table to remove authentication fields
        Schema::table('gurus', function (Blueprint $table) {
            // Remove authentication fields
            $authFields = ['name', 'email', 'password', 'username', 'remember_token', 'email_verified_at'];
            foreach ($authFields as $field) {
                if (Schema::hasColumn('gurus', $field)) {
                    $table->dropColumn($field);
                }
            }
            
            // Add user_id foreign key
            if (!Schema::hasColumn('gurus', 'user_id')) {
                $table->unsignedBigInteger('user_id')->unique()->after('id');
                $table->foreign('user_id')->references('id')->on('users_central')->onDelete('cascade');
            }
        });

        // Update siswa table to remove authentication fields
        Schema::table('siswa', function (Blueprint $table) {
            // Remove authentication fields
            $authFields = ['name', 'email', 'password', 'username', 'remember_token', 'email_verified_at'];
            foreach ($authFields as $field) {
                if (Schema::hasColumn('siswa', $field)) {
                    $table->dropColumn($field);
                }
            }
            
            // Add user_id foreign key if not exists
            if (!Schema::hasColumn('siswa', 'user_id')) {
                $table->unsignedBigInteger('user_id')->unique()->after('id');
                $table->foreign('user_id')->references('id')->on('users_central')->onDelete('cascade');
            }
        });

        // Update admin table to remove authentication fields
        Schema::table('admins', function (Blueprint $table) {
            // Remove authentication fields
            $authFields = ['name', 'email', 'password', 'username', 'remember_token', 'email_verified_at'];
            foreach ($authFields as $field) {
                if (Schema::hasColumn('admins', $field)) {
                    $table->dropColumn($field);
                }
            }
            
            // Add user_id foreign key
            if (!Schema::hasColumn('admins', 'user_id')) {
                $table->unsignedBigInteger('user_id')->unique()->after('id');
                $table->foreign('user_id')->references('id')->on('users_central')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_central');
        
        // Restore authentication fields to profile tables if needed
        // This would require more complex logic for production
    }
};
