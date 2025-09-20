<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class PracticalSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah tabel 'practicals' ada
        if (!Schema::hasTable('practicals')) {
            $this->command->error('❌ Tabel practicals tidak ditemukan!');
            return;
        }

        // Ambil user guru (role = 'guru') untuk foreign key
        $guruIds = DB::table('users')->where('role', 'guru')->pluck('id');
        if ($guruIds->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada user dengan role "guru", praktikum tidak bisa dibuat.');
            return;
        }

        // Ambil subject yang ada
        $subjectIds = DB::table('subjects')->pluck('id');
        if ($subjectIds->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada subject, praktikum tidak bisa dibuat.');
            return;
        }

        // Bersihkan data lama
        DB::table('practicals')->delete();

        $today = Carbon::today();
        $practicals = [
            [
                'guru_id' => $guruIds->random(),
                'subject_id' => $subjectIds->random(),
                'judul' => 'Praktik Injeksi Intramuskular',
                'deskripsi' => 'Melatih teknik injeksi intramuskular pada manekin sesuai SOP.',
                'tanggal' => $today->copy()->addDays(3),
                'lokasi' => 'Lab Keperawatan Lantai 2',
                'durasi' => 60,
                'tools' => json_encode(['Sarung tangan steril', 'Spuit 3cc', 'Alkohol swab', 'Kapas', 'Tempat sampah infeksius']),
                'bahan' => json_encode(['Manekin lengan', 'Cairan simulasi vaksin']),
                'instruksi' => '1. Pakai APD lengkap.\n2. Identifikasi area injeksi.\n3. Lakukan desinfeksi.\n4. Lakukan injeksi dengan sudut 90 derajat.\n5. Buang alat di tempat yang ditentukan.',
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'guru_id' => $guruIds->random(),
                'subject_id' => $subjectIds->random(),
                'judul' => 'Pemasangan Infus',
                'deskripsi' => 'Praktik pemasangan infus pada manekin dengan teknik aseptik.',
                'tanggal' => $today->copy()->addDays(5),
                'lokasi' => 'Lab Keperawatan Lantai 3',
                'durasi' => 90,
                'tools' => json_encode(['Infus set', 'Cairan NaCl 0.9%', 'Gunting verban', 'Plester', 'Trokar']),
                'bahan' => json_encode(['Manekin lengan dengan vena simulasi']),
                'instruksi' => '1. Cuci tangan dan pakai sarung tangan.\n2. Pasang tourniquet.\n3. Lakukan pungsi vena.\n4. Fiksasi jarum dan selang.\n5. Atur tetesan sesuai instruksi.',
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'guru_id' => $guruIds->random(),
                'subject_id' => $subjectIds->random(),
                'judul' => 'Pemeriksaan Tanda Vital',
                'deskripsi' => 'Praktik pengukuran tekanan darah, nadi, respirasi, dan suhu tubuh.',
                'tanggal' => $today->copy()->addDays(7),
                'lokasi' => 'Ruang Simulasi Klinik',
                'durasi' => 45,
                'tools' => json_encode(['Tensimeter', 'Stetoskop', 'Termometer digital', 'Jam tangan dengan detik', 'Buku catatan']),
                'bahan' => json_encode(['Manekin pasien', 'Form pencatatan tanda vital']),
                'instruksi' => '1. Jelaskan prosedur ke pasien (manekin).\n2. Ukur tekanan darah.\n3. Hitung nadi dan respirasi selama 1 menit.\n4. Ukur suhu aksila.\n5. Catat hasil dengan rapi.',
                'is_published' => false, // Belum dipublikasikan
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('practicals')->insert($practicals);

        $this->command->info('✅ PracticalSeeder: ' . count($practicals) . ' data praktikum berhasil disimpan.');
    }
}
