<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixMissingSiswaProfiles extends Command
{
    protected $signature   = 'fix:siswa-profiles';
    protected $description = 'Buat profil siswa yang hilang di tabel siswa untuk semua user dengan role=siswa';

    public function handle(): int
    {
        $siswaUsers = DB::table('users_central')
            ->where('role', 'siswa')
            ->whereNull('deleted_at')
            ->get(['id', 'name', 'phone']);

        $created = 0;
        foreach ($siswaUsers as $u) {
            $exists = DB::table('siswa')->where('user_id', $u->id)->exists();
            if ($exists) continue;

            // Cek juga soft-deleted
            $softDeleted = DB::table('siswa')
                ->where('user_id', $u->id)
                ->whereNotNull('deleted_at')
                ->first();

            if ($softDeleted) {
                DB::table('siswa')->where('id', $softDeleted->id)->update([
                    'deleted_at' => null,
                    'updated_at' => now(),
                ]);
                $this->line("Restored siswa profile for user_id={$u->id} ({$u->name})");
                $created++;
                continue;
            }

            $kelas = DB::table('classes')->first();

            DB::table('siswa')->insert([
                'user_id'       => $u->id,
                'nis'           => 'SIS' . str_pad($u->id, 6, '0', STR_PAD_LEFT) . rand(10,99),
                'nisn'          => '000' . str_pad($u->id, 5, '0', STR_PAD_LEFT) . rand(10,99),
                'jenis_kelamin' => 'L',
                'tempat_lahir'  => 'Tidak diketahui',
                'tanggal_lahir' => now()->subYears(17)->format('Y-m-d'),
                'alamat'        => '-',
                'no_telepon'    => $u->phone ?? '0000000000',
                'kelas_id'      => $kelas?->id,
                'major'         => 'Umum',
                'tahun_ajaran'  => date('Y') . '/' . (date('Y') + 1),
                'status'        => 'aktif',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            $this->line("Created siswa profile for user_id={$u->id} ({$u->name})");
            $created++;
        }

        $this->info("Selesai. {$created} profil siswa dibuat/diperbaiki.");
        return 0;
    }
}
