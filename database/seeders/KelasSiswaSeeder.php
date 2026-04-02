<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class KelasSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat data kelas untuk admin
        $kelasData = [
            [
                'name' => 'X RPL 1',
                'code' => 'X-RPL-1',
                'grade' => 'X',
                'major' => 'Rekayasa Perangkat Lunak',
                'description' => 'Kelas X Rekayasa Perangkat Lunak 1',
                'academic_year' => '2025/2026',
                'status' => 'active',
                'capacity' => 36,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'X RPL 2',
                'code' => 'X-RPL-2',
                'grade' => 'X',
                'major' => 'Rekayasa Perangkat Lunak',
                'description' => 'Kelas X Rekayasa Perangkat Lunak 2',
                'academic_year' => '2025/2026',
                'status' => 'active',
                'capacity' => 36,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'XI RPL 1',
                'code' => 'XI-RPL-1',
                'grade' => 'XI',
                'major' => 'Rekayasa Perangkat Lunak',
                'description' => 'Kelas XI Rekayasa Perangkat Lunak 1',
                'academic_year' => '2025/2026',
                'status' => 'active',
                'capacity' => 36,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'XI RPL 2',
                'code' => 'XI-RPL-2',
                'grade' => 'XI',
                'major' => 'Rekayasa Perangkat Lunak',
                'description' => 'Kelas XI Rekayasa Perangkat Lunak 2',
                'academic_year' => '2025/2026',
                'status' => 'active',
                'capacity' => 36,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'XII RPL 1',
                'code' => 'XII-RPL-1',
                'grade' => 'XII',
                'major' => 'Rekayasa Perangkat Lunak',
                'description' => 'Kelas XII Rekayasa Perangkat Lunak 1',
                'academic_year' => '2025/2026',
                'status' => 'active',
                'capacity' => 36,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'XII RPL 2',
                'code' => 'XII-RPL-2',
                'grade' => 'XII',
                'major' => 'Rekayasa Perangkat Lunak',
                'description' => 'Kelas XII Rekayasa Perangkat Lunak 2',
                'academic_year' => '2025/2026',
                'status' => 'active',
                'capacity' => 36,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert kelas data
        foreach ($kelasData as $kelas) {
            Kelas::create($kelas);
        }

        // Buat data siswa untuk absensi
        $siswaData = [];
        $kelasList = Kelas::all();

        foreach ($kelasList as $kelas) {
            // Generate 10-15 siswa per kelas
            $jumlahSiswa = rand(10, 15);
            
            for ($i = 1; $i <= $jumlahSiswa; $i++) {
                $siswaData[] = [
                    'name' => $this->generateRandomName(),
                    'email' => 'siswa' . strtolower(str_replace([' ', '-'], '', $kelas->code)) . $i . '@lms-trimurti.sch.id',
                    'username' => strtolower(str_replace([' ', '-'], '', $kelas->code)) . $i,
                    'password' => Hash::make('password123'),
                    'role' => 'siswa',
                    'kelas_id' => $kelas->id,
                    'phone' => $this->generateRandomPhone(),
                    'address' => $this->generateRandomAddress(),
                    'birth_date' => $this->generateRandomBirthDate(),
                    'gender' => $this->generateRandomGender(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert siswa data in chunks
        collect($siswaData)->chunk(100)->each(function ($chunk) {
            User::insert($chunk->toArray());
        });

        $this->command->info('Data kelas dan siswa berhasil dibuat!');
        $this->command->info('Total kelas: ' . count($kelasData));
        $this->command->info('Total siswa: ' . count($siswaData));
    }

    /**
     * Generate random Indonesian name
     */
    private function generateRandomName(): string
    {
        $firstNames = ['Ahmad', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fajar', 'Gita', 'Hendra', 'Indra', 'Joko', 'Kartika', 'Lina', 'Maya', 'Nina', 'Omar', 'Putri', 'Rani', 'Siti', 'Toni', 'Umar', 'Vera', 'Wahyu'];
        $lastNames = ['Santoso', 'Wijaya', 'Saputra', 'Putri', 'Hidayat', 'Sutrisno', 'Gunawan', 'Pratiwi', 'Sari', 'Permata', 'Kusuma', 'Mulyani', 'Rahayu', 'Nugroho', 'Suharto', 'Sukarno', 'Purnama', 'Wibowo'];

        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    /**
     * Generate random phone number
     */
    private function generateRandomPhone(): string
    {
        return '08' . rand(100000000, 999999999);
    }

    /**
     * Generate random address
     */
    private function generateRandomAddress(): string
    {
        $streets = ['Jl. Merdeka', 'Jl. Sudirman', 'Jl. Gatot Subroto', 'Jl. Ahmad Yani', 'Jl. Pemuda', 'Jl. Veteran', 'Jl. Diponegoro'];
        $cities = ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Semarang', 'Medan', 'Palembang', 'Makassar'];
        $provinces = ['DKI Jakarta', 'Jawa Barat', 'Jawa Timur', 'DI Yogyakarta', 'Jawa Tengah', 'Sumatera Utara', 'Sumatera Selatan', 'Sulawesi Selatan'];

        return $streets[array_rand($streets)] . ' No. ' . rand(1, 100) . ', ' . $cities[array_rand($cities)] . ', ' . $provinces[array_rand($provinces)];
    }

    /**
     * Generate random birth date
     */
    private function generateRandomBirthDate(): string
    {
        // Generate age between 15-18 years old
        $year = date('Y') - rand(15, 18);
        $month = rand(1, 12);
        $day = rand(1, 28);
        
        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }

    /**
     * Generate random gender
     */
    private function generateRandomGender(): string
    {
        return rand(0, 1) ? 'L' : 'P';
    }
}
