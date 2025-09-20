<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ExamSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah tabel 'exams' ada
        if (!Schema::hasTable('exams')) {
            $this->command->error('❌ Tabel exams tidak ditemukan!');
            return;
        }

        // Ambil user guru (role = 'guru') untuk created_by
        $guruIds = DB::table('users')->where('role', 'guru')->pluck('id');
        if ($guruIds->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada user dengan role "guru", exam tidak bisa dibuat.');
            return;
        }

        // Bersihkan data lama
        DB::table('exams')->delete();

        $now = Carbon::now();

        // Gunakan setTime(jam, menit) tanpa leading zero untuk hindari false positive "invalid numeric literal"
        $exams = [
            [
                'title' => 'Ujian Tengah Semester - Keperawatan Dasar',
                'description' => 'Ujian teori dan studi kasus tentang prinsip dasar keperawatan.',
                'duration' => 90, // menit
                'start_time' => $now->copy()->addDays(2)->setTime(8, 0),   // ← 08,00 → 8,0
                'end_time' => $now->copy()->addDays(2)->setTime(9, 30),     // ← 09,30 → 9,30
                'total_questions' => 50,
                'passing_score' => 65,
                'max_score' => 100,
                'is_published' => true,
                'created_by' => $guruIds->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Ujian Praktik Injeksi',
                'description' => 'Ujian keterampilan praktik injeksi intramuskular dan subkutan.',
                'duration' => 30,
                'start_time' => $now->copy()->addDays(5)->setTime(10, 0),   // ← 10,00 → 10,0
                'end_time' => $now->copy()->addDays(5)->setTime(10, 30),
                'total_questions' => 5,
                'passing_score' => 75,
                'max_score' => 100,
                'is_published' => true,
                'created_by' => $guruIds->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Try Out Ujian Akhir Semester',
                'description' => 'Latihan ujian akhir semester untuk mempersiapkan siswa.',
                'duration' => 120,
                'start_time' => $now->copy()->addDays(10)->setTime(7, 30),  // ← 07,30 → 7,30
                'end_time' => $now->copy()->addDays(10)->setTime(9, 30),
                'total_questions' => 80,
                'passing_score' => 60,
                'max_score' => 100,
                'is_published' => false,
                'created_by' => $guruIds->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('exams')->insert($exams);

        $this->command->info('✅ ExamSeeder: ' . count($exams) . ' data ujian berhasil disimpan.');
    }
}
