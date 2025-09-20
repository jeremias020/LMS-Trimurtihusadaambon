<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah tabel 'questions' ada
        if (!Schema::hasTable('questions')) {
            $this->command->error('❌ Tabel questions tidak ditemukan!');
            return;
        }

        // Ambil minimal 1 exam untuk foreign key
        $exams = DB::table('exams')->limit(3)->get();
        if ($exams->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada data exam. Silakan jalankan ExamSeeder terlebih dahulu.');
            return;
        }

        // Ambil user guru (untuk user_id)
        $guruIds = DB::table('users')->where('role', 'guru')->pluck('id');
        if ($guruIds->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada user dengan role "guru".');
            return;
        }

        // Hapus data lama
        DB::table('question_options')->delete(); // Hapus dulu karena punya foreign key ke questions
        DB::table('questions')->delete();

        $guruId = $guruIds->random();
        $questionIdCounter = 1;

        foreach ($exams as $exam) {
            $questions = [
                [
                    'exam_id' => $exam->id,
                    'user_id' => $guruId,
                    'title' => 'Apa fungsi utama dari tensimeter?',
                    'type' => 'pilihan_ganda',
                    'content' => 'Alat ini digunakan untuk mengukur...',
                    'score' => 5,
                    'explanation' => 'Tensimeter digunakan untuk mengukur tekanan darah pasien.',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'exam_id' => $exam->id,
                    'user_id' => $guruId,
                    'title' => 'Jelaskan prosedur cuci tangan medis yang benar!',
                    'type' => 'essai',
                    'content' => 'Tuliskan langkah-langkah cuci tangan medis sesuai standar WHO.',
                    'score' => 10,
                    'explanation' => 'Cuci tangan medis terdiri dari 6 langkah selama minimal 40-60 detik.',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'exam_id' => $exam->id,
                    'user_id' => $guruId,
                    'title' => 'Apa yang dimaksud dengan infeksi nosokomial?',
                    'type' => 'pilihan_ganda',
                    'content' => 'Infeksi yang didapat di lingkungan rumah sakit disebut...',
                    'score' => 5,
                    'explanation' => 'Infeksi nosokomial adalah infeksi yang didapat selama perawatan di fasilitas kesehatan.',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            foreach ($questions as &$question) {
                $questionId = $questionIdCounter++;
                $question['id'] = $questionId; // Set ID agar bisa referensi di question_options
            }

            DB::table('questions')->insert($questions);

            // Insert pilihan jawaban untuk soal pilihan ganda
            $options = [
                // Untuk soal 1
                ['question_id' => 1, 'option_text' => 'Mengukur suhu tubuh', 'is_correct' => false, 'order' => 1],
                ['question_id' => 1, 'option_text' => 'Mengukur tekanan darah', 'is_correct' => true, 'order' => 2],
                ['question_id' => 1, 'option_text' => 'Menghitung denyut nadi', 'is_correct' => false, 'order' => 3],
                ['question_id' => 1, 'option_text' => 'Mengukur respirasi', 'is_correct' => false, 'order' => 4],

                // Untuk soal 3
                ['question_id' => 3, 'option_text' => 'Infeksi dari luar negeri', 'is_correct' => false, 'order' => 1],
                ['question_id' => 3, 'option_text' => 'Infeksi bawaan lahir', 'is_correct' => false, 'order' => 2],
                ['question_id' => 3, 'option_text' => 'Infeksi yang didapat di rumah sakit', 'is_correct' => true, 'order' => 3],
                ['question_id' => 3, 'option_text' => 'Infeksi karena makanan', 'is_correct' => false, 'order' => 4],
            ];

            DB::table('question_options')->insert($options);
        }

        $totalQuestions = DB::table('questions')->count();
        $this->command->info("✅ QuestionSeeder: {$totalQuestions} soal berhasil disimpan untuk " . $exams->count() . " ujian.");
    }
}
