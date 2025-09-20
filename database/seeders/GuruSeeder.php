<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
    public function run()
    {
        $users = User::where('role', 'guru')->get();

        if ($users->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada user dengan role "guru". Guru tidak bisa dibuat.');
            return;
        }

        foreach ($users as $user) {
            // Cek apakah guru dengan user_id ini sudah ada
            $existingGuru = Guru::where('user_id', $user->id)->first();

            if ($existingGuru) {
                $this->command->info("⚠️ Guru dengan user_id {$user->id} sudah ada, skip...");
                continue;
            }

            // Cek apakah NIP sudah digunakan
            $nip = 'NIP' . str_pad($user->id, 6, '0', STR_PAD_LEFT);
            $existingNip = Guru::where('nip', $nip)->first();

            if ($existingNip) {
                // Generate NIP yang unik jika sudah ada
                $nip = 'NIP' . str_pad($user->id + 100, 6, '0', STR_PAD_LEFT);
                $this->command->info("⚠️ NIP {$nip} sudah digunakan, generate baru: {$nip}");
            }

            Guru::create([
                'user_id' => $user->id,
                'nip' => $nip,
                'nama' => $user->name,
                'jenis_kelamin' => $user->gender ?? 'L',
                'tempat_lahir' => 'Ambon',
                'tanggal_lahir' => $user->birth_date ?? '1980-01-01',
                'alamat' => $user->address ?? 'Jl. Guru, Ambon',
                'no_telepon' => $user->phone ?? '081234567890',
                'email' => $user->email,
                'mata_pelajaran' => 'Keperawatan Dasar',
                'pendidikan_terakhir' => 'S1 Keperawatan',
                'foto' => null,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $count = Guru::count();
        $this->command->info("✅ GuruSeeder: {$count} data guru berhasil disimpan.");
    }
}
