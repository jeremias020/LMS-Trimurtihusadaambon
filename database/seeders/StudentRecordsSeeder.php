<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentRecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users with role 'siswa'
        $siswaUsers = User::where('role', 'siswa')->get();
        
        if ($siswaUsers->isEmpty()) {
            $this->command->error('No siswa users found. Please run KelasSiswaSeeder first.');
            return;
        }

        $createdCount = 0;
        $updatedCount = 0;

        foreach ($siswaUsers as $user) {
            // Check if student record already exists
            $existingStudent = Student::where('user_id', $user->id)->first();
            
            if ($existingStudent) {
                // Update existing record
                $existingStudent->update([
                    'nis' => $existingStudent->nis ?? $this->generateNIS($user->kelas_id),
                    'nisn' => $existingStudent->nisn ?? $this->generateNISN(),
                    'jenis_kelamin' => $existingStudent->jenis_kelamin ?? $this->getJenisKelamin($user->gender),
                    'tempat_lahir' => $existingStudent->tempat_lahir ?? $this->generateTempatLahir(),
                    'tanggal_lahir' => $existingStudent->tanggal_lahir ?? $user->birth_date,
                    'alamat' => $existingStudent->alamat ?? $user->address,
                    'no_telepon' => $existingStudent->no_telepon ?? $user->phone,
                    'kelas_id' => $user->kelas_id,
                    'major' => $existingStudent->major ?? $this->getMajorFromKelas($user->kelas_id),
                    'tahun_ajaran' => $existingStudent->tahun_ajaran ?? '2025/2026',
                ]);
                $updatedCount++;
            } else {
                // Create new student record
                Student::create([
                    'user_id' => $user->id,
                    'nis' => $this->generateNIS($user->kelas_id),
                    'nisn' => $this->generateNISN(),
                    'jenis_kelamin' => $this->getJenisKelamin($user->gender),
                    'tempat_lahir' => $this->generateTempatLahir(),
                    'tanggal_lahir' => $user->birth_date,
                    'alamat' => $user->address,
                    'no_telepon' => $user->phone,
                    'kelas_id' => $user->kelas_id,
                    'major' => $this->getMajorFromKelas($user->kelas_id),
                    'tahun_ajaran' => '2025/2026',
                ]);
                $createdCount++;
            }
        }

        $this->command->info('Student records created/updated successfully!');
        $this->command->info("Created: {$createdCount} new records");
        $this->command->info("Updated: {$updatedCount} existing records");
        $this->command->info("Total siswa users: {$siswaUsers->count()}");
    }

    /**
     * Generate NIS (Nomor Induk Siswa)
     */
    private function generateNIS($kelasId): string
    {
        $kelas = \App\Models\Kelas::find($kelasId);
        $kelasCode = $kelas ? substr($kelas->code, -2) : '01';
        $random = str_pad(mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        return date('Y') . $kelasCode . $random;
    }

    /**
     * Generate NISN (Nomor Induk Siswa Nasional)
     */
    private function generateNISN(): string
    {
        return str_pad(mt_rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT);
    }

    /**
     * Convert gender to jenis_kelamin
     */
    private function getJenisKelamin($gender): string
    {
        return match($gender) {
            'L', 'male' => 'L',
            'P', 'female' => 'P',
            default => 'L'
        };
    }

    /**
     * Generate tempat lahir
     */
    private function generateTempatLahir(): string
    {
        $cities = ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Semarang', 'Medan', 'Palembang', 'Makassar'];
        return $cities[array_rand($cities)];
    }

    /**
     * Get major from kelas
     */
    private function getMajorFromKelas($kelasId): string
    {
        $kelas = \App\Models\Kelas::find($kelasId);
        return $kelas ? $kelas->major : 'Rekayasa Perangkat Lunak';
    }
}
