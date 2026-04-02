<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SampleAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all students
        $students = User::where('role', 'siswa')->get();
        
        if ($students->isEmpty()) {
            $this->command->error('No students found. Please run KelasSiswaSeeder first.');
            return;
        }

        // Create sample attendance data for the last 7 days
        $statuses = ['hadir', 'izin', 'sakit', 'alpha'];
        $attendanceData = [];

        foreach ($students as $student) {
            // Generate attendance for last 7 days
            for ($i = 0; $i < 7; $i++) {
                $date = Carbon::now()->subDays($i);
                
                // Skip weekends
                if ($date->isWeekend()) {
                    continue;
                }

                // Random status (70% hadir, 10% izin, 10% sakit, 10% alpha)
                $statusWeights = ['hadir' => 70, 'izin' => 10, 'sakit' => 10, 'alpha' => 10];
                $status = $this->getRandomStatus($statusWeights);

                $attendanceData[] = [
                    'siswa_id' => $student->id,
                    'tanggal' => $date->format('Y-m-d'),
                    'status' => $status,
                    'keterangan' => $this->getRandomKeterangan($status),
                    'waktu_masuk' => $status === 'hadir' ? $date->copy()->setTime(7, rand(15, 45)) : null,
                    'waktu_keluar' => $status === 'hadir' ? $date->copy()->setTime(14, rand(0, 30)) : null,
                    'recorded_by' => 1, // Admin user ID
                    'type' => 'regular',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert data in chunks
        collect($attendanceData)->chunk(100)->each(function ($chunk) {
            Attendance::insert($chunk->toArray());
        });

        $this->command->info('Sample attendance data created successfully!');
        $this->command->info('Total attendance records: ' . count($attendanceData));
    }

    /**
     * Get random status based on weights
     */
    private function getRandomStatus(array $weights): string
    {
        $totalWeight = array_sum($weights);
        $random = mt_rand(1, $totalWeight);
        $currentWeight = 0;

        foreach ($weights as $status => $weight) {
            $currentWeight += $weight;
            if ($random <= $currentWeight) {
                return $status;
            }
        }

        return 'hadir';
    }

    /**
     * Get random keterangan based on status
     */
    private function getRandomKeterangan(string $status): ?string
    {
        $keterangan = match($status) {
            'izin' => [
                'Izin orang tua',
                'Izin dokter',
                'Izin pribadi',
                'Izin keluarga'
            ],
            'sakit' => [
                'Sakit demam',
                'Sakit kepala',
                'Sakit perut',
                'Sakit flu'
            ],
            'alpha' => [
                'Tanpa keterangan',
                'Terlambat datang',
                'Pulang lebih awal'
            ],
            default => null
        };

        return $keterangan ? $keterangan[array_rand($keterangan)] : null;
    }
}
