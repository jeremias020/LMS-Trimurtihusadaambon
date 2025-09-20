<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Skip jika tabel tidak ada
        if (!Schema::hasTable('users')) {
            $this->command->error('❌ Tabel users tidak ditemukan!');
            return;
        }

        // Nonaktifkan foreign key checks untuk avoid constraint errors
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Data users
        $users = [
            // Admin
            [
                'name' => 'Admin Utama',
                'email' => 'admin@trimurtihusada.sch.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Jl. Pendidikan No. 123, Ambon',
                'birth_date' => '1985-05-15',
                'gender' => 'L',
                'status' => 'active',
                'photo' => "https://ui-avatars.com/api/?name=Admin+Utama&background=3b82f6&color=fff",
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Guru
            [
                'name' => 'Dr. Ahmad Santoso, M.Kep',
                'email' => 'ahmad.santoso@trimurtihusada.sch.id',
                'password' => Hash::make('guru123'),
                'role' => 'guru',
                'phone' => '081234567891',
                'address' => 'Jl. Guru No. 45, Ambon',
                'birth_date' => '1980-08-20',
                'gender' => 'L',
                'status' => 'active',
                'photo' => "https://ui-avatars.com/api/?name=Ahmad+Santoso&background=10b981&color=fff",
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dewi Anggraeni, S.Kep., M.Kep',
                'email' => 'dewi.anggraeni@trimurtihusada.sch.id',
                'password' => Hash::make('guru456'),
                'role' => 'guru',
                'phone' => '081234567892',
                'address' => 'Jl. Perawat No. 67, Ambon',
                'birth_date' => '1982-11-30',
                'gender' => 'P',
                'status' => 'active',
                'photo' => "https://ui-avatars.com/api/?name=Dewi+Anggraeni&background=ec4899&color=fff",
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Siswa
            [
                'name' => 'Agus Setiawan',
                'email' => 'agus.setiawan@siswa.trimurtihusada.sch.id',
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
                'phone' => '081234567893',
                'address' => 'Jl. Siswa No. 1, Ambon',
                'birth_date' => '2007-03-10',
                'gender' => 'L',
                'status' => 'active',
                'photo' => "https://ui-avatars.com/api/?name=Agus+Setiawan&background=8b5cf6&color=fff",
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bambang Sutrisno',
                'email' => 'bambang.sutrisno@siswa.trimurtihusada.sch.id',
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
                'phone' => '081234567894',
                'address' => 'Jl. Siswa No. 2, Ambon',
                'birth_date' => '2007-06-15',
                'gender' => 'L',
                'status' => 'active',
                'photo' => "https://ui-avatars.com/api/?name=Bambang+Sutrisno&background=f59e0b&color=fff",
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Citra Dewi',
                'email' => 'citra.dewi@siswa.trimurtihusada.sch.id',
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
                'phone' => '081234567895',
                'address' => 'Jl. Siswa No. 3, Ambon',
                'birth_date' => '2007-09-20',
                'gender' => 'P',
                'status' => 'active',
                'photo' => "https://ui-avatars.com/api/?name=Citra+Dewi&background=ef4444&color=fff",
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Insert data
        $insertedCount = 0;
        $updatedCount = 0;

        foreach ($users as $user) {
            $existing = DB::table('users')->where('email', $user['email'])->first();

            if ($existing) {
                DB::table('users')->where('email', $user['email'])->update($user);
                $updatedCount++;
            } else {
                DB::table('users')->insert($user);
                $insertedCount++;
            }
        }

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info("✅ UserSeeder berhasil!");
        $this->command->info("📊 Total: " . count($users) . " users");
        $this->command->info("🆕 Inserted: " . $insertedCount . " users");
        $this->command->info("🔄 Updated: " . $updatedCount . " users");

        // Tampilkan detail per role
        $adminCount = DB::table('users')->where('role', 'admin')->count();
        $guruCount = DB::table('users')->where('role', 'guru')->count();
        $siswaCount = DB::table('users')->where('role', 'siswa')->count();

        $this->command->info("👨‍💼 Admin: " . $adminCount);
        $this->command->info("👨‍🏫 Guru: " . $guruCount);
        $this->command->info("👨‍🎓 Siswa: " . $siswaCount);
    }
}
