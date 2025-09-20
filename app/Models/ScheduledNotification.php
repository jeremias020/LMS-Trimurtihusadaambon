<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledNotification extends Model
{
    use HasFactory;

    protected $table = 'scheduled_notifications';

    protected $fillable = [
        'jadwal_ujian_id',
        'notification_type',
        'scheduled_at',
        'sent_at',
        'status',
        'error_message'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';

    const TYPE_H7 = 'h7';  // 7 hari sebelum
    const TYPE_H3 = 'h3';  // 3 hari sebelum
    const TYPE_H1 = 'h1';  // 1 hari sebelum
    const TYPE_H0 = 'h0';  // Hari H

    protected $attributes = [
        'status' => self::STATUS_PENDING
    ];

    /**
     * Relationship dengan jadwal ujian
     */
    public function jadwalUjian(): BelongsTo
    {
        return $this->belongsTo(JadwalUjian::class);
    }

    /**
     * Scope untuk notifikasi yang pending
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope untuk notifikasi yang siap dikirim
     */
    public function scopeReadyToSend($query)
    {
        return $query->pending()
                    ->where('scheduled_at', '<=', now());
    }

    /**
     * Get notification type labels
     */
    public static function getTypeLabels()
    {
        return [
            self::TYPE_H7 => 'H-7 (7 hari sebelum)',
            self::TYPE_H3 => 'H-3 (3 hari sebelum)',
            self::TYPE_H1 => 'H-1 (1 hari sebelum)',
            self::TYPE_H0 => 'H-0 (Hari ujian)'
        ];
    }

    /**
     * Get status labels
     */
    public static function getStatusLabels()
    {
        return [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_SENT => 'Terkirim',
            self::STATUS_FAILED => 'Gagal'
        ];
    }

    /**
     * Generate notification message
     */
    public function generateNotificationMessage()
    {
        $jadwal = $this->jadwalUjian;
        $messages = [
            self::TYPE_H7 => "📅 Reminder: Ujian {$jadwal->mata_pelajaran} akan dilaksanakan dalam 7 hari pada {$jadwal->date_time}. Persiapkan diri dengan baik!",
            self::TYPE_H3 => "⏰ Reminder: Ujian {$jadwal->mata_pelajaran} tinggal 3 hari lagi pada {$jadwal->date_time}. Jangan lupa untuk belajar dan review materi.",
            self::TYPE_H1 => "🚨 Important: Besok ujian {$jadwal->mata_pelajaran} pada {$jadwal->date_time}. Pastikan Anda sudah siap dan datang tepat waktu!",
            self::TYPE_H0 => "📝 Hari ini ujian {$jadwal->mata_pelajaran} pada {$jadwal->date_time} di {$jadwal->ruangan}. Good luck!"
        ];

        return $messages[$this->notification_type] ?? 'Notification message';
    }

    /**
     * Generate notification title
     */
    public function generateNotificationTitle()
    {
        $jadwal = $this->jadwalUjian;
        $titles = [
            self::TYPE_H7 => "Ujian {$jadwal->mata_pelajaran} - H-7",
            self::TYPE_H3 => "Ujian {$jadwal->mata_pelajaran} - H-3", 
            self::TYPE_H1 => "Ujian {$jadwal->mata_pelajaran} - Besok",
            self::TYPE_H0 => "Ujian {$jadwal->mata_pelajaran} - Hari Ini"
        ];

        return $titles[$this->notification_type] ?? 'Notification';
    }

    /**
     * Mark as sent
     */
    public function markAsSent()
    {
        $this->update([
            'status' => self::STATUS_SENT,
            'sent_at' => now(),
            'error_message' => null
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed($errorMessage = null)
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error_message' => $errorMessage
        ]);
    }
}