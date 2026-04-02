<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class MigrateUsersToSeparateTablesCommand extends Command
{
    protected $signature = 'migrate:users-separate';
    protected $description = 'Migrate users data to separate tables (admin, guru, siswa)';

    public function handle()
    {
        $this->info('=== MIGRATING USERS TO SEPARATE TABLES ===');
        
        try {
            $users = User::all();
            $migratedCount = 0;
            
            foreach ($users as $user) {
                switch ($user->role) {
                    case 'admin':
                        $this->migrateAdmin($user);
                        $migratedCount++;
                        break;
                    
                    case 'guru':
                        $this->migrateGuru($user);
                        $migratedCount++;
                        break;
                    
                    case 'siswa':
                        $this->migrateSiswa($user);
                        $migratedCount++;
                        break;
                }
            }
            
            $this->info("✅ Successfully migrated {$migratedCount} users!");
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
    
    private function migrateAdmin($user)
    {
        // Check if already exists
        $existing = Admin::where('email', $user->email)->first();
        if ($existing) {
            $this->line("ℹ️  Admin {$user->name} already exists");
            return;
        }
        
        Admin::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password, // Already hashed
            'username' => $user->username ?? 'admin_' . $user->id,
            'phone' => $user->phone,
            'address' => $user->address,
            'birth_date' => $user->birth_date,
            'gender' => $user->gender,
            'photo' => $user->avatar,
            'status' => $user->is_active ? 'aktif' : 'tidak_aktif',
            'email_verified_at' => $user->email_verified_at,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
        
        $this->line("✅ Migrated admin: {$user->name}");
    }
    
    private function migrateGuru($user)
    {
        // Check if already exists
        $existing = Guru::where('email', $user->email)->first();
        if ($existing) {
            $this->line("ℹ️  Guru {$user->name} already exists");
            return;
        }
        
        Guru::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password, // Already hashed
            'username' => $user->username ?? 'guru_' . $user->id,
            'nip' => $user->nis_nip ?? 'GUR' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
            'jenis_kelamin' => $user->gender ?? 'L',
            'tempat_lahir' => 'Ambon',
            'tanggal_lahir' => $user->birth_date ?? now()->subYears(30)->format('Y-m-d'),
            'alamat' => $user->address ?? 'Alamat tidak tersedia',
            'no_telepon' => $user->phone ?? '0000000000',
            'email_pribadi' => $user->email,
            'mata_pelajaran' => 'Mata Pelajaran Umum',
            'pendidikan_terakhir' => 'S1',
            'jurusan_pendidikan' => 'Pendidikan',
            'tahun_mulai_kerja' => now()->subYears(5)->year,
            'photo' => $user->avatar,
            'status' => $user->is_active ? 'aktif' : 'tidak_aktif',
            'email_verified_at' => $user->email_verified_at,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
        
        $this->line("✅ Migrated guru: {$user->name}");
    }
    
    private function migrateSiswa($user)
    {
        // Check if already exists
        $existing = Student::where('email', $user->email)->first();
        if ($existing) {
            $this->line("ℹ️  Siswa {$user->name} already exists");
            return;
        }
        
        // Check if user_id already exists
        $existingByUserId = Student::where('user_id', $user->id)->first();
        if ($existingByUserId) {
            $this->line("ℹ️  Siswa with user_id {$user->id} already exists");
            return;
        }
        
        Student::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password, // Already hashed
            'username' => $user->username ?? 'siswa_' . $user->id,
            'user_id' => $user->id, // Keep reference
            'nis' => $user->nis_nip ?? 'SIS' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
            'nisn' => '000' . str_pad($user->id, 7, '0', STR_PAD_LEFT),
            'jenis_kelamin' => $user->gender ?? 'L',
            'tempat_lahir' => 'Ambon',
            'tanggal_lahir' => $user->birth_date ?? now()->subYears(18)->format('Y-m-d'),
            'alamat' => $user->address ?? 'Alamat tidak tersedia',
            'no_telepon' => $user->phone ?? '0000000000',
            'kelas_id' => $user->kelas_id ?? 1,
            'major' => 'Keperawatan',
            'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1),
            'nama_ortu' => 'Orang Tua ' . $user->name,
            'no_telepon_ortu' => '0812345678' . str_pad($user->id, 1, '0', STR_PAD_LEFT),
            'golongan_darah' => 'A',
            'foto' => $user->avatar,
            'status' => 'aktif',
            'email_verified_at' => $user->email_verified_at,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
        
        $this->line("✅ Migrated siswa: {$user->name}");
    }
}
