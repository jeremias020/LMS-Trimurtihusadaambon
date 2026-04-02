<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing users (optional - uncomment if you want to reset)
        // User::truncate();
        
        // Create default classes first if they don't exist
        $this->createDefaultClasses();
        
        // Get sample class ID
        $kelasId = Kelas::first()?->id;
        
        // Temporarily disable auto-create events
        User::withoutEvents(function () use ($kelasId) {
            $this->createUsers($kelasId);
        });
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Output success message
        $this->command->info('✅ Default users created successfully!');
        $this->command->info('==================================================');
        $this->command->info('🔐 LOGIN CREDENTIALS:');
        $this->command->info('==================================================');
        $this->command->info('👑 ADMIN:');
        $this->command->info('   Email: admin@trimurti.edu');
        $this->command->info('   Password: admin123');
        $this->command->info('   Role: Administrator');
        $this->command->info('');
        $this->command->info('👨‍🏫 GURU (Teachers):');
        $this->command->info('   1. Email: guru@trimurti.edu');
        $this->command->info('      Password: guru123');
        $this->command->info('      Name: Dr. Sari Kusuma');
        $this->command->info('   2. Email: guru2@trimurti.edu');
        $this->command->info('      Password: guru123');
        $this->command->info('      Name: Ns. Budi Santoso');
        $this->command->info('');
        $this->command->info('👨‍🎓 SISWA (Students):');
        $this->command->info('   1. Email: siswa@trimurti.edu');
        $this->command->info('      Password: siswa123');
        $this->command->info('      Name: Andi Pratama');
        $this->command->info('   2. Email: siswa2@trimurti.edu');
        $this->command->info('      Password: siswa123');
        $this->command->info('      Name: Siti Nurhayati');
        $this->command->info('   3. Email: siswa3@trimurti.edu');
        $this->command->info('      Password: siswa123');
        $this->command->info('      Name: Made Wijaya');
        $this->command->info('==================================================');
    }
    
    /**
     * Create default classes if they don't exist
     */
    private function createDefaultClasses(): void
    {
        // Create sample classes for health vocational school
        $classes = [
            [
                'name' => 'X Keperawatan A',
                'code' => 'X-KEP-A',
                'grade' => 'X',
                'major' => 'Keperawatan',
                'description' => 'Kelas X Jurusan Keperawatan A',
                'capacity' => 30,
                'academic_year' => '2024/2025',
                'status' => 'active'
            ],
            [
                'name' => 'X Keperawatan B',
                'code' => 'X-KEP-B',
                'grade' => 'X',
                'major' => 'Keperawatan',
                'description' => 'Kelas X Jurusan Keperawatan B',
                'capacity' => 30,
                'academic_year' => '2024/2025',
                'status' => 'active'
            ],
            [
                'name' => 'XI Keperawatan A',
                'code' => 'XI-KEP-A',
                'grade' => 'XI',
                'major' => 'Keperawatan',
                'description' => 'Kelas XI Jurusan Keperawatan A',
                'capacity' => 28,
                'academic_year' => '2024/2025',
                'status' => 'active'
            ],
            [
                'name' => 'XII Keperawatan A',
                'code' => 'XII-KEP-A',
                'grade' => 'XII',
                'major' => 'Keperawatan',
                'description' => 'Kelas XII Jurusan Keperawatan A',
                'capacity' => 25,
                'academic_year' => '2024/2025',
                'status' => 'active'
            ],
            [
                'name' => 'X Farmasi',
                'code' => 'X-FAR',
                'grade' => 'X',
                'major' => 'Farmasi',
                'description' => 'Kelas X Jurusan Farmasi',
                'capacity' => 25,
                'academic_year' => '2024/2025',
                'status' => 'active'
            ],
            [
                'name' => 'XI Farmasi',
                'code' => 'XI-FAR',
                'grade' => 'XI',
                'major' => 'Farmasi',
                'description' => 'Kelas XI Jurusan Farmasi',
                'capacity' => 23,
                'academic_year' => '2024/2025',
                'status' => 'active'
            ],
        ];
        
        foreach ($classes as $class) {
            Kelas::firstOrCreate(
                ['code' => $class['code']],
                $class
            );
        }
        
        $this->command->info('✅ Default classes created/verified.');
    }
    
    /**
     * Create users without auto-events
     */
    private function createUsers($kelasId): void
    {
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@trimurti.edu'],
            [
                'name' => 'Administrator System',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Jl. Trimurti Husada No. 1, Ambon',
                'birth_date' => '1985-01-15',
                'gender' => 'L',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        
        // Create Guru (Teacher) Users
        $guru1 = User::firstOrCreate(
            ['email' => 'guru@trimurti.edu'],
            [
                'name' => 'Dr. Sari Kusuma, S.Kep., M.Kep',
                'username' => 'guru1',
                'password' => Hash::make('guru123'),
                'role' => 'guru',
                'phone' => '082345678901',
                'address' => 'Jl. Pendidikan No. 15, Ambon',
                'birth_date' => '1980-05-20',
                'gender' => 'P',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        
        $guru2 = User::firstOrCreate(
            ['email' => 'guru2@trimurti.edu'],
            [
                'name' => 'Ns. Budi Santoso, S.Kep., M.Kep',
                'username' => 'guru2',
                'password' => Hash::make('guru123'),
                'role' => 'guru',
                'phone' => '083456789012',
                'address' => 'Jl. Kesehatan No. 25, Ambon',
                'birth_date' => '1982-08-10',
                'gender' => 'L',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        
        // Create Siswa (Student) Users  
        $siswa1 = User::firstOrCreate(
            ['email' => 'siswa@trimurti.edu'],
            [
                'name' => 'Andi Pratama',
                'username' => 'siswa1',
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
                'kelas_id' => $kelasId,
                'phone' => '085567890123',
                'address' => 'Jl. Mahasiswa No. 10, Ambon',
                'birth_date' => '2005-03-12',
                'gender' => 'L',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        
        $siswa2 = User::firstOrCreate(
            ['email' => 'siswa2@trimurti.edu'],
            [
                'name' => 'Siti Nurhayati',
                'username' => 'siswa2',
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
                'kelas_id' => $kelasId,
                'phone' => '086678901234',
                'address' => 'Jl. Pelajar No. 20, Ambon',
                'birth_date' => '2005-07-25',
                'gender' => 'P',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        
        $siswa3 = User::firstOrCreate(
            ['email' => 'siswa3@trimurti.edu'],
            [
                'name' => 'Made Wijaya',
                'username' => 'siswa3',
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
                'kelas_id' => $kelasId,
                'phone' => '087789012345',
                'address' => 'Jl. Pemuda No. 30, Ambon',
                'birth_date' => '2005-11-08',
                'gender' => 'L',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
    }
}
