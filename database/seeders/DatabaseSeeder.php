<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan foreign key constraints sementara
        Schema::disableForeignKeyConstraints();

        // Hapus data dari tabel utama saja — HAPUS 'competency_indicators' karena tidak ada
        $tables = [
            'material_downloads',
            'materials',
            'practical_scores',
            'practicals',
            'assignment_submissions',
            'assignments',
            // 'competency_indicators', ← HAPUS INI — TIDAK ADA DI MIGRATION
            'scores',
            'results',
            'question_options',
            'questions',
            'exams',
            'attendances',
            'notifications',
            'siswa',
            'guru',
            'profiles',
            'practice_modules',
            'criteria',
            'subjects',
            'kelas',
            'users',
            'settings',
            'feedback'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->delete();
                // Reset auto increment hanya di development
                if (app()->environment('local', 'testing')) {
                    DB::statement("ALTER TABLE {$table} AUTO_INCREMENT = 1");
                }
            }
        }

        // Aktifkan kembali foreign key constraints
        Schema::enableForeignKeyConstraints();

        // Jalankan seeder secara berurutan — PERBAIKI URUTAN: UserSeeder HARUS PALING AWAL!
       $this->call([
    UserSeeder::class,
    KelasSeeder::class,
    SubjectSeeder::class,
    SettingSeeder::class,
    SiswaSeeder::class,
    GuruSeeder::class, // ✅ UBAH: TeacherSeeder → GuruSeeder
    ProfileSeeder::class,
    PracticeModuleSeeder::class,
    CriteriaSeeder::class,
    MaterialSeeder::class,
    ExamSeeder::class,
    QuestionSeeder::class,
    AssignmentSeeder::class,
    PracticalSeeder::class,
    AttendanceSeeder::class,
    NotificationSeeder::class,
]);

        $this->command->info('✅ DatabaseSeeder: Semua seeder berhasil dijalankan.');
    }
}
