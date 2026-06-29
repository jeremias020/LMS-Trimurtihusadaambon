<?php

namespace App\Observers;

use App\Models\Material;
use App\Models\Notification;
use App\Models\Siswa;
use Illuminate\Support\Facades\Log;

class MaterialObserver
{
    /**
     * Saat materi baru dibuat dan langsung dipublish.
     */
    public function created(Material $material): void
    {
        if ($material->published_at) {
            $this->sendNotificationToStudents($material);
        }
    }

    /**
     * Saat materi diupdate — kirim notifikasi jika baru dipublish.
     */
    public function updated(Material $material): void
    {
        if ($material->wasChanged('published_at') && $material->published_at) {
            $this->sendNotificationToStudents($material);
        }
    }

    /**
     * Kirim notifikasi ke siswa sekelas.
     */
    private function sendNotificationToStudents(Material $material): void
    {
        try {
            if (!$material->kelas_id) {
                // Tidak ada kelas spesifik — skip
                return;
            }

            $students = Siswa::where('kelas_id', $material->kelas_id)
                ->whereNotNull('user_id')
                ->get(['user_id']);

            if ($students->isEmpty()) {
                return;
            }

            $subjectName = $material->subject?->name ?? $material->subject?->nama ?? 'Mata Pelajaran';
            $guruName    = $material->guru?->name ?? 'Guru';
            $kelasName   = $material->kelas?->name ?? 'Kelas';
            $pesan       = "Materi baru \"{$material->title}\" untuk {$subjectName} telah ditambahkan. Silakan cek halaman materi.";

            foreach ($students as $student) {
                Notification::create([
                    'title'         => 'Materi Baru: ' . $material->title,
                    'message'       => $pesan,
                    'judul'         => 'Materi Baru: ' . $material->title,
                    'pesan'         => $pesan,
                    'tipe'          => 'material',
                    'type'          => 'material',
                    'tipe_notifikasi' => 'info',
                    'tipe_penerima' => 'user',
                    'receiver_type' => 'user',
                    'penerima_id'   => $student->user_id,
                    'receiver_id'   => $student->user_id,
                    'is_read'       => false,
                    'status'        => 'belum_dibaca',
                    'prioritas'     => 'sedang',
                    'priority'      => 'medium',
                    'created_by'    => $material->guru_id,
                    'pengirim_id'   => $material->guru_id,
                    'sender_id'     => $material->guru_id,
                    'data'          => [
                        'material_id' => $material->id,
                        'subject'     => $subjectName,
                        'teacher'     => $guruName,
                        'class'       => $kelasName,
                    ],
                ]);
            }

            Log::info('Material notifications sent', [
                'material_id'    => $material->id,
                'students_count' => $students->count(),
            ]);

        } catch (\Throwable $e) {
            // Jangan crash app hanya karena notifikasi gagal
            Log::error('Failed to send material notifications: ' . $e->getMessage(), [
                'material_id' => $material->id,
            ]);
        }
    }
}
