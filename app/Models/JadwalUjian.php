<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class JadwalUjian extends Model
{
    use HasFactory;

    protected $table = 'jadwal_ujian';

    protected $fillable = [
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'mata_pelajaran',
        'kelas_id',
        'jenis_ujian',
        'pengawas_id',
        'ruangan',
        'deskripsi',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i'
    ];

    // Jenis ujian yang tersedia
    const JENIS_QUIZ = 'quiz';
    const JENIS_UTS = 'uts';
    const JENIS_UAS = 'uas';
    const JENIS_PRAKTIK = 'praktik';

    // Status ujian
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    protected $attributes = [
        'status' => self::STATUS_SCHEDULED
    ];

    /**
     * Relationship dengan model Kelas
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Relationship dengan pengawas (User)
     */
    public function pengawas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengawas_id');
    }

    /**
     * Relationship dengan scheduled notifications
     */
    public function scheduledNotifications(): HasMany
    {
        return $this->hasMany(ScheduledNotification::class);
    }

    /**
     * Scope untuk ujian yang akan datang
     */
    public function scopeUpcoming($query)
    {
        return $query->where('tanggal', '>=', now()->toDateString())
                    ->where('status', self::STATUS_SCHEDULED)
                    ->orderBy('tanggal')
                    ->orderBy('waktu_mulai');
    }

    /**
     * Scope untuk ujian hari ini
     */
    public function scopeToday($query)
    {
        return $query->where('tanggal', now()->toDateString());
    }

    /**
     * Scope berdasarkan jenis ujian
     */
    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_ujian', $jenis);
    }

    /**
     * Scope berdasarkan kelas
     */
    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    /**
     * Get list jenis ujian
     */
    public static function getJenisUjianList()
    {
        return [
            self::JENIS_QUIZ => 'Quiz/Kuis',
            self::JENIS_UTS => 'Ujian Tengah Semester',
            self::JENIS_UAS => 'Ujian Akhir Semester',
            self::JENIS_PRAKTIK => 'Ujian Praktik'
        ];
    }

    /**
     * Get list status ujian
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_SCHEDULED => 'Terjadwal',
            self::STATUS_ONGOING => 'Berlangsung',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan'
        ];
    }

    /**
     * Get formatted date time
     */
    public function getDateTimeAttribute()
    {
        return $this->tanggal->format('d/m/Y') . ' ' . 
               Carbon::parse($this->waktu_mulai)->format('H:i') . ' - ' .
               Carbon::parse($this->waktu_selesai)->format('H:i');
    }

    /**
     * Get durasi ujian dalam menit
     */
    public function getDurationAttribute()
    {
        $mulai = Carbon::parse($this->waktu_mulai);
        $selesai = Carbon::parse($this->waktu_selesai);
        return $mulai->diffInMinutes($selesai);
    }

    /**
     * Get durasi dalam format jam:menit
     */
    public function getDurationFormattedAttribute()
    {
        $duration = $this->duration;
        $hours = floor($duration / 60);
        $minutes = $duration % 60;
        
        if ($hours > 0) {
            return $hours . ' jam ' . ($minutes > 0 ? $minutes . ' menit' : '');
        }
        return $minutes . ' menit';
    }

    /**
     * Get hari hingga ujian
     */
    public function getDaysUntilExamAttribute()
    {
        return now()->startOfDay()->diffInDays($this->tanggal, false);
    }

    /**
     * Check apakah ujian sudah dimulai
     */
    public function hasStarted()
    {
        $examDateTime = Carbon::parse($this->tanggal->format('Y-m-d') . ' ' . Carbon::parse($this->waktu_mulai)->format('H:i:s'));
        return now()->greaterThanOrEqualTo($examDateTime);
    }

    /**
     * Check apakah ujian sudah selesai
     */
    public function hasEnded()
    {
        $examEndTime = Carbon::parse($this->tanggal->format('Y-m-d') . ' ' . Carbon::parse($this->waktu_selesai)->format('H:i:s'));
        return now()->greaterThan($examEndTime);
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        $colors = [
            self::STATUS_SCHEDULED => 'primary',
            self::STATUS_ONGOING => 'warning',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger'
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Auto update status berdasarkan waktu
     */
    public function updateStatusBasedOnTime()
    {
        if ($this->hasEnded()) {
            $this->update(['status' => self::STATUS_COMPLETED]);
        } elseif ($this->hasStarted()) {
            $this->update(['status' => self::STATUS_ONGOING]);
        }
    }

    /**
     * Generate scheduled notifications untuk ujian ini
     */
    public function generateScheduledNotifications()
    {
        // Clear existing notifications
        $this->scheduledNotifications()->delete();
        
        $examDateTime = Carbon::parse($this->tanggal->format('Y-m-d') . ' ' . Carbon::parse($this->waktu_mulai)->format('H:i:s'));
        
        $notifications = [
            [
                'notification_type' => 'h7',
                'scheduled_at' => $examDateTime->copy()->subDays(7),
            ],
            [
                'notification_type' => 'h3',
                'scheduled_at' => $examDateTime->copy()->subDays(3),
            ],
            [
                'notification_type' => 'h1',
                'scheduled_at' => $examDateTime->copy()->subDay(),
            ],
            [
                'notification_type' => 'h0',
                'scheduled_at' => $examDateTime->copy()->subHours(2), // 2 jam sebelum ujian
            ]
        ];
        
        foreach ($notifications as $notification) {
            // Hanya buat notifikasi untuk waktu yang akan datang
            if ($notification['scheduled_at']->greaterThan(now())) {
                $this->scheduledNotifications()->create([
                    'notification_type' => $notification['notification_type'],
                    'scheduled_at' => $notification['scheduled_at'],
                    'status' => 'pending'
                ]);
            }
        }
    }

    /**
     * Get siswa yang terkena ujian ini
     */
    public function getSiswaList()
    {
        return User::where('role', 'siswa')
                  ->where('kelas_id', $this->kelas_id)
                  ->where('status', 'active')
                  ->get();
    }

    /**
     * Event handlers
     */
    protected static function booted()
    {
        // Generate notifications setelah ujian dibuat
        static::created(function ($jadwal) {
            $jadwal->generateScheduledNotifications();
        });
        
        // Update notifications jika jadwal diubah
        static::updated(function ($jadwal) {
            if ($jadwal->isDirty(['tanggal', 'waktu_mulai']) && $jadwal->status === self::STATUS_SCHEDULED) {
                $jadwal->generateScheduledNotifications();
            }
        });
    }
}