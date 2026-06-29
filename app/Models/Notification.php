<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model tunggal untuk tabel `notifications`.
 * Kolom DB: id, title, message, tipe_penerima, penerima_id, tipe_notifikasi,
 *           action_url, is_read, read_at, data, created_by,
 *           + (dari migration _000020): pengirim_id, sender_id, receiver_id,
 *             receiver_type, tipe, type, judul, pesan, url_aksi,
 *             prioritas, priority, status, scheduled_at
 */
class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // Kolom asli (migration _000018)
        'title',
        'message',
        'tipe_penerima',
        'penerima_id',
        'tipe_notifikasi',
        'action_url',
        'is_read',
        'read_at',
        'data',
        'created_by',
        // Kolom tambahan (migration _000020)
        'pengirim_id',
        'sender_id',
        'receiver_id',
        'receiver_type',
        'tipe',
        'type',
        'judul',
        'pesan',
        'url_aksi',
        'prioritas',
        'priority',
        'status',
        'scheduled_at',
    ];

    protected $casts = [
        'is_read'      => 'boolean',
        'read_at'      => 'datetime',
        'scheduled_at' => 'datetime',
        'data'         => 'array',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
        'deleted_at'   => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Scopes ─────────────────────────────────────────────────────────────────

    /** Belum dibaca: is_read = false ATAU read_at = null */
    public function scopeUnread($query)
    {
        return $query->where(function ($q) {
            $q->where('is_read', false)->orWhereNull('read_at');
        });
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true)->whereNotNull('read_at');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('penerima_id', $userId)
              ->orWhere('tipe_penerima', 'semua');
        });
    }

    public function scopeScheduled($query)
    {
        return $query->whereNotNull('scheduled_at')->where('scheduled_at', '>', now());
    }

    public function scopeReadyToSend($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('scheduled_at')->orWhere('scheduled_at', '<=', now());
        });
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    /** Judul notifikasi — pakai kolom `title` atau fallback ke `judul` */
    public function getDisplayTitleAttribute(): string
    {
        return $this->attributes['title'] ?? $this->attributes['judul'] ?? 'Notifikasi';
    }

    /** Pesan notifikasi — pakai kolom `message` atau fallback ke `pesan` */
    public function getDisplayMessageAttribute(): string
    {
        return $this->attributes['message'] ?? $this->attributes['pesan'] ?? '';
    }

    /** URL aksi */
    public function getDisplayActionUrlAttribute(): ?string
    {
        return $this->attributes['action_url'] ?? $this->attributes['url_aksi'] ?? null;
    }

    /** Tipe notifikasi */
    public function getDisplayTypeAttribute(): string
    {
        return $this->attributes['type'] ?? $this->attributes['tipe'] ?? $this->attributes['tipe_notifikasi'] ?? 'info';
    }

    /** Icon FontAwesome (tanpa prefix) */
    public function getIconNameAttribute(): string
    {
        return match($this->display_type) {
            'exam'         => 'calendar-check',
            'assignment'   => 'tasks',
            'material'     => 'book',
            'practical'    => 'flask',
            'attendance'   => 'user-check',
            'announcement' => 'bullhorn',
            'warning', 'peringatan' => 'exclamation-triangle',
            'success', 'sukses'     => 'check-circle',
            'error', 'danger'       => 'times-circle',
            default                 => 'bell',
        };
    }

    /** Warna Bootstrap */
    public function getColorAttribute(): string
    {
        return match($this->display_type) {
            'exam'         => 'danger',
            'assignment'   => 'warning',
            'material'     => 'info',
            'practical'    => 'success',
            'attendance'   => 'primary',
            'announcement' => 'secondary',
            'warning', 'peringatan' => 'warning',
            'success', 'sukses'     => 'success',
            'error', 'danger'       => 'danger',
            default                 => 'primary',
        };
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at?->diffForHumans() ?? '—';
    }

    public function getIsUnreadAttribute(): bool
    {
        return !$this->is_read || is_null($this->read_at);
    }

    // ── Methods ────────────────────────────────────────────────────────────────

    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
            'status'  => 'terbaca',
        ]);
    }

    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
            'status'  => 'belum_dibaca',
        ]);
    }
}
