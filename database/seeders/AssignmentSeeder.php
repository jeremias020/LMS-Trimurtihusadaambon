<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AssignmentSeeder extends Seeder
{
    public function run()
    {
        // Hanya jalankan jika tabel assignments ada
        if (!Schema::hasTable('assignments')) {
            $this->command->error('Tabel assignments tidak ditemukan!');
            return;
        }

        // Bersihkan data lama
        DB::table('assignments')->delete();

        // Ambil user guru (role = 'guru') untuk foreign key
        $guruIds = DB::table('users')->where('role', 'guru')->pluck('id');
        if ($guruIds->isEmpty()) {
            $this->command->warn('Tidak ada user dengan role "guru", assignment tidak bisa dibuat.');
            return;
        }

        // Ambil subject yang ada
        $subjectIds = DB::table('subjects')->pluck('id');
        if ($subjectIds->isEmpty()) {
            $this->command->warn('Tidak ada subject, assignment tidak bisa dibuat.');
            return;
        }

        $assignments = [
            [
                'title' => 'Laporan Praktikum Keperawatan Dasar',
                'description' => 'Buat laporan observasi pasien selama 3 hari.',
                'deadline' => Carbon::now()->addWeek(),
                'max_score' => 100,
                'guru_id' => $guruIds->random(),
                'subject_id' => $subjectIds->random(),
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Studi Kasus Asuhan Keperawatan',
                'description' => 'Analisis studi kasus pasien dengan diabetes melitus.',
                'deadline' => Carbon::now()->addWeeks(2),
                'max_score' => 90,
                'guru_id' => $guruIds->random(),
                'subject_id' => $subjectIds->random(),
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Presentasi Teknik Injeksi',
                'description' => 'Presentasi kelompok tentang teknik injeksi intramuskular.',
                'deadline' => Carbon::now()->addWeeks(3),
                'max_score' => 85,
                'guru_id' => $guruIds->random(),
                'subject_id' => $subjectIds->random(),
                'is_published' => false, // Belum dipublikasikan
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('assignments')->insert($assignments);

        $this->command->info('✅ AssignmentSeeder: ' . count($assignments) . ' data assignment berhasil disimpan.');
    }
}
