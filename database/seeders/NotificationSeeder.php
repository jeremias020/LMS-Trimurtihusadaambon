<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users for testing
        $admin = User::where('role', 'admin')->first();
        $guru = User::where('role', 'guru')->first();
        $siswa = User::where('role', 'siswa')->first();

        if (!$admin || !$guru || !$siswa) {
            $this->command->info('Skipping notification seeding - required users not found');
            return;
        }

        // Create sample notifications
        $notifications = [
            [
                'pengirim_id' => $admin->id,
                'penerima_id' => $siswa->id,
                'tipe_penerima' => 'siswa',
                'tipe' => 'info',
                'judul' => 'Materi Baru Tersedia',
                'pesan' => 'Materi pembelajaran baru telah ditambahkan untuk mata pelajaran Anatomi Fisiologi.',
                'url_aksi' => '/siswa/materials',
                'prioritas' => 'sedang',
                'status' => 'belum_dibaca',
                'created_at' => now()->subHours(2),
            ],
            [
                'pengirim_id' => $guru->id,
                'penerima_id' => $siswa->id,
                'tipe_penerima' => 'siswa',
                'tipe' => 'sukses',
                'judul' => 'Tugas Berhasil Dikumpulkan',
                'pesan' => 'Tugas Anda untuk mata pelajaran Farmakologi telah berhasil dikumpulkan.',
                'url_aksi' => '/siswa/assignments',
                'prioritas' => 'rendah',
                'status' => 'terbaca',
                'read_at' => now()->subHour(),
                'created_at' => now()->subHours(3),
            ],
            [
                'pengirim_id' => $admin->id,
                'penerima_id' => $guru->id,
                'tipe_penerima' => 'guru',
                'tipe' => 'peringatan',
                'judul' => 'Reminder: Deadline Penilaian',
                'pesan' => 'Ada beberapa tugas yang belum dinilai dan deadline sudah dekat.',
                'url_aksi' => '/guru/assignments',
                'prioritas' => 'tinggi',
                'status' => 'belum_dibaca',
                'created_at' => now()->subMinutes(30),
            ],
            [
                'pengirim_id' => $admin->id,
                'penerima_id' => null,
                'tipe_penerima' => 'semua',
                'tipe' => 'sistem',
                'judul' => 'Maintenance Sistem',
                'pesan' => 'Akan dilakukan maintenance sistem pada hari Minggu pukul 02:00-04:00 WIT.',
                'url_aksi' => null,
                'prioritas' => 'sedang',
                'status' => 'belum_dibaca',
                'created_at' => now()->subMinutes(15),
            ],
            [
                'pengirim_id' => $guru->id,
                'penerima_id' => $siswa->id,
                'tipe_penerima' => 'siswa',
                'tipe' => 'info',
                'judul' => 'Jadwal Praktikum Diperbarui',
                'pesan' => 'Jadwal praktikum untuk minggu depan telah diperbarui. Silakan cek jadwal terbaru.',
                'url_aksi' => '/siswa/practicals',
                'prioritas' => 'sedang',
                'status' => 'belum_dibaca',
                'created_at' => now()->subMinutes(5),
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }

        $this->command->info('Sample notifications created successfully!');
    }
}