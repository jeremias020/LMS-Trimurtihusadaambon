<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCentralUserSystemCommand extends Command
{
    protected $signature = 'create:central-user-system';
    protected $description = 'Create modern central user system with separate profiles';

    public function handle()
    {
        $this->info('=== CREATING CENTRAL USER SYSTEM ===');
        
        try {
            // Step 1: Create users_central table
            $this->line('📋 Creating users_central table...');
            $this->createUsersCentralTable();
            
            // Step 2: Migrate existing users to central table
            $this->line('🔄 Migrating users to central table...');
            $this->migrateToCentralTable();
            
            // Step 3: Update profile tables to use user_id
            $this->line('🔗 Updating profile tables...');
            $this->updateProfileTables();
            
            // Step 4: Update models
            $this->line('📝 Updating models...');
            $this->updateModels();
            
            $this->info('✅ Central user system created successfully!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
    
    private function createUsersCentralTable()
    {
        Schema::dropIfExists('users_central');
        
        Schema::create('users_central', function ($table) {
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
        
        $this->line('✅ users_central table created');
    }
    
    private function migrateToCentralTable()
    {
        // Get all existing users from separate tables
        $centralUsers = [];
        
        // From admins table
        $admins = DB::table('admins')->get();
        foreach ($admins as $admin) {
            $centralUsers[] = [
                'name' => $admin->name ?? '',
                'email' => $admin->email ?? '',
                'password' => $admin->password ?? '',
                'username' => $admin->username ?? '',
                'role' => 'admin',
                'phone' => $admin->phone ?? '',
                'photo' => $admin->photo ?? '',
                'is_active' => ($admin->status ?? '') === 'aktif',
                'email_verified_at' => $admin->email_verified_at,
                'created_at' => $admin->created_at,
                'updated_at' => $admin->updated_at,
                'deleted_at' => $admin->deleted_at,
            ];
        }
        
        // From gurus table
        $gurus = DB::table('gurus')->get();
        foreach ($gurus as $guru) {
            $centralUsers[] = [
                'name' => $guru->name ?? '',
                'email' => $guru->email ?? '',
                'password' => $guru->password ?? '',
                'username' => $guru->username ?? '',
                'role' => 'guru',
                'phone' => $guru->no_telepon ?? '',
                'photo' => $guru->photo ?? '',
                'is_active' => ($guru->status ?? '') === 'aktif',
                'email_verified_at' => $guru->email_verified_at,
                'created_at' => $guru->created_at,
                'updated_at' => $guru->updated_at,
                'deleted_at' => $guru->deleted_at,
            ];
        }
        
        // From siswa table
        $siswas = DB::table('siswa')->get();
        foreach ($siswas as $siswa) {
            $centralUsers[] = [
                'name' => $siswa->name ?? '',
                'email' => $siswa->email ?? '',
                'password' => $siswa->password ?? '',
                'username' => $siswa->username ?? '',
                'role' => 'siswa',
                'phone' => $siswa->no_telepon ?? '',
                'photo' => $siswa->foto ?? '',
                'is_active' => ($siswa->status ?? '') === 'aktif',
                'email_verified_at' => $siswa->email_verified_at,
                'created_at' => $siswa->created_at,
                'updated_at' => $siswa->updated_at,
                'deleted_at' => $siswa->deleted_at,
            ];
        }
        
        // Insert into central table
        if (!empty($centralUsers)) {
            DB::table('users_central')->insert($centralUsers);
            $this->line('✅ Migrated ' . count($centralUsers) . ' users to central table');
        }
    }
    
    private function updateProfileTables()
    {
        // Update admins table
        Schema::table('admins', function ($table) {
            // Remove authentication fields
            $authFields = ['name', 'email', 'password', 'username', 'remember_token', 'email_verified_at', 'phone', 'photo', 'created_at', 'updated_at', 'deleted_at'];
            foreach ($authFields as $field) {
                if (Schema::hasColumn('admins', $field)) {
                    $table->dropColumn($field);
                }
            }
            
            // Add user_id
            if (!Schema::hasColumn('admins', 'user_id')) {
                $table->unsignedBigInteger('user_id')->unique()->after('id');
                $table->foreign('user_id')->references('id')->on('users_central')->onDelete('cascade');
            }
        });
        
        // Update gurus table
        Schema::table('gurus', function ($table) {
            // Remove authentication fields
            $authFields = ['name', 'email', 'password', 'username', 'remember_token', 'email_verified_at', 'no_telepon', 'photo', 'created_at', 'updated_at', 'deleted_at'];
            foreach ($authFields as $field) {
                if (Schema::hasColumn('gurus', $field)) {
                    $table->dropColumn($field);
                }
            }
            
            // Add user_id
            if (!Schema::hasColumn('gurus', 'user_id')) {
                $table->unsignedBigInteger('user_id')->unique()->after('id');
                $table->foreign('user_id')->references('id')->on('users_central')->onDelete('cascade');
            }
        });
        
        // Update siswa table
        Schema::table('siswa', function ($table) {
            // Remove authentication fields
            $authFields = ['name', 'email', 'password', 'username', 'remember_token', 'email_verified_at', 'no_telepon', 'foto', 'created_at', 'updated_at', 'deleted_at'];
            foreach ($authFields as $field) {
                if (Schema::hasColumn('siswa', $field)) {
                    $table->dropColumn($field);
                }
            }
            
            // Add user_id
            if (!Schema::hasColumn('siswa', 'user_id')) {
                $table->unsignedBigInteger('user_id')->unique()->after('id');
                $table->foreign('user_id')->references('id')->on('users_central')->onDelete('cascade');
            }
        });
        
        $this->line('✅ Profile tables updated');
    }
    
    private function updateModels()
    {
        // This would be handled manually by updating the model files
        $this->line('📝 Models need to be updated manually');
        $this->line('   - Update User model to use users_central table');
        $this->line('   - Update Admin model to extend Model instead of Authenticatable');
        $this->line('   - Update Guru model to extend Model instead of Authenticatable');
        $this->line('   - Update Student model to extend Model instead of Authenticatable');
        $this->line('   - Add user_id relationships to profile models');
    }
}
