<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddThreePracticalAssessmentsSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Adding 3 practical assessments with complete criteria...');
        
        // Get guru
        $guru = DB::table('users')->where('email', 'guru@trimurti.sch.id')->first();
        if (!$guru) {
            $this->command->error('Guru not found! Please run WorkingPenilaianSeeder first.');
            return;
        }
        $guruId = $guru->id;

        // Get subject
        $subject = DB::table('subjects')->where('name', 'Keperawatan Dasar')->first();
        if (!$subject) {
            $subjectId = DB::table('subjects')->insertGetId([
                'name' => 'Keperawatan Dasar',
                'code' => 'KD-101',
                'description' => 'Mata pelajaran keperawatan dasar',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $subjectId = $subject->id;
        }

        // Create 3 practical assessments
        $practicals = [
            [
                'judul' => 'Praktikum Tindakan Injeksi Intramuskular',
                'deskripsi' => 'Praktikum melakukan tindakan injeksi intramuskular pada pasien simulasi dengan teknik yang benar dan aman',
                'subject_id' => $subjectId,
                'guru_id' => $guruId,
                'max_score' => 100,
                'tanggal' => now()->addDays(7),
                'lokasi' => 'Lab Keperawatan A',
                'durasi' => 120,
                'tools' => 'Syringe 5ml, Alcohol swab, Cotton, Medication tray, Tourniquet',
                'bahan' => 'Obat simulasi, Desinfektan, Handscoon, Masker',
                'instruksi' => 'Lakukan injeksi IM sesuai SOP keperawatan: 1) Cek order, 2) Cek pasien, 3) Cek obat, 4) Persiapkan peralatan, 5) Lakukan teknik aseptik, 6) Lakukan injeksi, 7) Dokumentasi',
                'is_published' => true,
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '10:00:00',
                'skill_level' => 'Menengah',
                'keselamatan' => 'Gunakan APD lengkap: handscoon, masker, dan ikuti protokol keselamatan injeksi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Praktikum Perawatan Luka Modern',
                'deskripsi' => 'Praktikum melakukan perawatan luka dengan metode modern dressing dan teknik aseptik',
                'subject_id' => $subjectId,
                'guru_id' => $guruId,
                'max_score' => 100,
                'tanggal' => now()->addDays(14),
                'lokasi' => 'Lab Keperawatan B',
                'durasi' => 90,
                'tools' => 'Gauze, Dressing set, Scissors, Forceps, Sterile tray',
                'bahan' => 'NaCl 0.9%, Povidone iodine, Sterile gauze, Transparent dressing',
                'instruksi' => 'Lakukan perawatan luka: 1) Cek jenis luka, 2) Bersihkan luka, 3) Aplikasikan antiseptik, 4) Dressing modern, 5) Dokumentasi',
                'is_published' => true,
                'waktu_mulai' => '09:00:00',
                'waktu_selesai' => '10:30:00',
                'skill_level' => 'Menengah',
                'keselamatan' => 'Gunakan handscoon steril dan ikuti protokol infeksi kontrol',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Praktikum Pemasangan Infus',
                'deskripsi' => 'Praktikum pemasangan infus IV pada pasien simulasi dengan teknik yang benar dan aman',
                'subject_id' => $subjectId,
                'guru_id' => $guruId,
                'max_score' => 100,
                'tanggal' => now()->addDays(21),
                'lokasi' => 'Lab Keperawatan C',
                'durasi' => 150,
                'tools' => 'IV set, Catheter gauge 20G, Tourniquet, IV pole, Scissors',
                'bahan' => 'Infus solution, Alcohol swab, Tape, Plaster, Label',
                'instruksi' => 'Lakukan pemasangan infus: 1) Cek order, 2) Cek pasien, 3) Pilih vena, 4) Persiapkan peralatan, 5) Tourniquet, 6) Desinfeksi, 7) Pasang catheter, 8) Fix infus, 9) Dokumentasi',
                'is_published' => true,
                'waktu_mulai' => '08:30:00',
                'waktu_selesai' => '11:00:00',
                'skill_level' => 'Lanjutan',
                'keselamatan' => 'Ikuti protokol pemasangan infus dan pastikan keamanan pasien',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $createdPracticals = [];
        foreach ($practicals as $practical) {
            $practicalId = DB::table('practicals')->insertGetId($practical);
            $createdPracticals[$practicalId] = $practical['judul'];
            $this->command->info("✅ Created practical: {$practical['judul']}");
        }

        // Create comprehensive assessment criteria
        $criteriaTemplates = [
            'persiapan' => [
                ['nama' => 'Peralatan lengkap dan siap', 'deskripsi' => 'Memastikan semua peralatan tersedia dan dalam kondisi baik', 'bobot' => 0.20],
                ['nama' => 'Standar APD lengkap', 'deskripsi' => 'Menggunakan APD sesuai standar keselamatan', 'bobot' => 0.15],
                ['nama' => 'Area kerja steril', 'deskripsi' => 'Menyiapkan area kerja yang steril dan aman', 'bobot' => 0.15],
            ],
            'pelaksanaan' => [
                ['nama' => 'Tindakan sesuai SOP', 'deskripsi' => 'Melakukan tindakan sesuai standar operasional prosedur', 'bobot' => 0.25],
                ['nama' => 'Teknik yang benar', 'deskripsi' => 'Menggunakan teknik yang benar dan efisien', 'bobot' => 0.20],
                ['nama' => 'Keamanan pasien', 'deskripsi' => 'Memastikan keamanan dan kenyamanan pasien', 'bobot' => 0.20],
            ],
            'hasil' => [
                ['nama' => 'Hasil optimal', 'deskripsi' => 'Mencapai hasil yang diharapkan dengan kualitas baik', 'bobot' => 0.30],
                ['nama' => 'Dokumentasi lengkap', 'deskripsi' => 'Mendokumentasikan tindakan dengan lengkap dan benar', 'bobot' => 0.20],
            ],
            'sikap' => [
                ['nama' => 'Komunikasi efektif', 'deskripsi' => 'Berkomunikasi dengan pasien dengan baik', 'bobot' => 0.15],
                ['nama' => 'Sikap profesional', 'deskripsi' => 'Menunjukkan sikap profesional dan etika keperawatan', 'bobot' => 0.20],
            ],
        ];

        $totalCriteria = 0;
        foreach ($createdPracticals as $practicalId => $practicalJudul) {
            foreach ($criteriaTemplates as $kategori => $criteriaList) {
                foreach ($criteriaList as $criteria) {
                    DB::table('kriteria_penilaian')->insert([
                        'nama' => $criteria['nama'],
                        'kategori' => $kategori,
                        'deskripsi' => $criteria['deskripsi'],
                        'bobot' => $criteria['bobot'],
                        'mata_praktik' => 'Keperawatan Dasar',
                        'tingkat_kelas' => 'XI',
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $totalCriteria++;
                }
            }
            $this->command->info("✅ Created criteria for: {$practicalJudul}");
        }

        $this->command->info('✅ 3 Practical assessments with complete criteria created successfully!');
        $this->command->info('📊 Summary:');
        $this->command->info('   - 3 New practical assessments created');
        $this->command->info('   - ' . $totalCriteria . ' Assessment criteria created');
        $this->command->info('   - Criteria categories: Persiapan, Pelaksanaan, Hasil, Sikap');
        $this->command->info('   - Subject: Keperawatan Dasar');
        $this->command->info('   - Level: XI');
        
        $this->command->info('');
        $this->command->info('🎉 Ready for assessment!');
        $this->command->info('📱 Visit: /guru/penilaian/create');
        $this->command->info('🎯 Now you have 4 practical assessments with complete criteria!');
    }
}
