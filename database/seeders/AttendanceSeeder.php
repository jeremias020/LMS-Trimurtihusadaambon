<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah tabel 'attendances' ada
        if (!Schema::hasTable('attendances')) {
            $this->command->error('❌ Tabel attendances tidak ditemukan!');
            return;
        }

        // Ambil semua siswa (role = 'siswa')
        $siswaIds = DB::table('users')->where('role', 'siswa')->pluck('id');
        if ($siswaIds->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada user dengan role "siswa", absensi tidak bisa dibuat.');
            return;
        }

        // Bersihkan data lama
        DB::table('attendances')->delete();

        $attendances = [];
        $startDate = Carbon::now()->subDays(30); // 30 hari ke belakang
        $endDate = Carbon::now();

        // Generate absensi untuk setiap siswa, setiap hari dalam rentang
        foreach ($siswaIds as $siswaId) {
            for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
                // Skip weekend (Sabtu & Minggu)
                if ($date->isWeekend()) {
                    continue;
                }

                // Random status absensi
                $status = $this->getRandomAttendanceStatus();
                $keterangan = $status === 'alpha' ? 'Tanpa keterangan' : null;

                // Untuk status hadir, buat TIMESTAMP lengkap
                if ($status === 'hadir') {
                    $waktuMasuk = $date->copy()->setTime(7, 0, 0)->format('Y-m-d H:i:s'); // ✅ TIMESTAMP LENGKAP
                    $waktuKeluar = $date->copy()->setTime(14, 30, 0)->format('Y-m-d H:i:s'); // ✅ TIMESTAMP LENGKAP
                } else {
                    $waktuMasuk = null;
                    $waktuKeluar = null;
                }

                $attendances[] = [
                    'siswa_id' => $siswaId,
                    'tanggal' => $date->toDateString(),
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'waktu_masuk' => $waktuMasuk, // ✅ TIMESTAMP ATAU NULL
                    'waktu_keluar' => $waktuKeluar, // ✅ TIMESTAMP ATAU NULL
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert batch
        if (!empty($attendances)) {
            DB::table('attendances')->insert($attendances);
            $this->command->info('✅ AttendanceSeeder: ' . count($attendances) . ' data absensi berhasil disimpan.');
        } else {
            $this->command->warn('⚠️ Tidak ada data absensi yang di-generate.');
        }
    }

    /**
     * Helper: Generate status absensi acak dengan probabilitas realistis
     */
    private function getRandomAttendanceStatus()
    {
        // 85% hadir, 10% izin, 3% sakit, 2% alpha
        $statuses = array_merge(
            array_fill(0, 85, 'hadir'),
            array_fill(0, 10, 'izin'),
            array_fill(0, 3, 'sakit'),
            array_fill(0, 2, 'alpha')
        );

        return $statuses[array_rand($statuses)];
    }
}
