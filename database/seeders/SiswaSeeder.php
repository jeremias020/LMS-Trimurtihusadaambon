<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        $kelas = Kelas::first();
        if (!$kelas) {
            $this->command->error('❌ Tidak ada data kelas. Silakan jalankan KelasSeeder terlebih dahulu.');
            return;
        }

        $users = User::where('role', 'siswa')->get();
        if ($users->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada user dengan role "siswa". Student tidak bisa dibuat.');
            return;
        }

        // Hapus data lama agar tidak duplikat
        // Student::whereNotNull('id')->delete();

        foreach ($users as $user) {
            Student::create([
                'user_id' => $user->id,
                'nis' => 'NIS' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'nisn' => 'NISN' . str_pad($user->id, 8, '0', STR_PAD_LEFT),
                'jenis_kelamin' => $user->gender ?? 'L',
                'tempat_lahir' => 'Ambon',
                'tanggal_lahir' => $user->birth_date ?? '2007-01-01',
                'alamat' => $user->address ?? 'Jl. Siswa, Ambon',
                'no_telepon' => $user->phone ?? '081234567890',
                'kelas_id' => $kelas->id,
                'major' => 'Keperawatan',
                'tahun_ajaran' => '2024/2025',
                'nama_ortu' => 'Orang Tua ' . $user->name,
                'no_telepon_ortu' => '08123456789' . $user->id,
                'golongan_darah' => ['A', 'B', 'AB', 'O'][rand(0, 3)],
                'riwayat_penyakit' => null,
                'alergi' => null,
                'info_kesehatan' => null,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $count = $users->count();
        $this->command->info("✅ SiswaSeeder: {$count} data siswa berhasil disimpan.");
    }
}
