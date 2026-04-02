<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'class_subject_id',
        'date',
        'status',
        'note',
        'created_by',
        'recorded_by',
        'type', // regular atau praktik
        'practical_id', // ID praktik terkait (jika type = praktik)
        'subject_id', // ID mata pelajaran
    ];

    protected $casts = [
        'date' => 'date',
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'siswa_id', 'user_id');
    }

    // Scopes
    public function scopePresent($query)
    {
        return $query->where('status', 'hadir');
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'alpha');
    }

    public function scopePermission($query)
    {
        return $query->whereIn('status', ['izin', 'sakit']);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeByMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)
                    ->whereMonth('date', $month);
    }

    // Accessors
    public function getDurationAttribute()
    {
        if ($this->waktu_masuk && $this->waktu_keluar) {
            return $this->waktu_masuk->diffInMinutes($this->waktu_keluar);
        }

        return null;
    }

    public function getDurationFormattedAttribute()
    {
        $minutes = $this->duration;
        if ($minutes) {
            $hours = floor($minutes / 60);
            $minutes = $minutes % 60;
            return sprintf('%dh %dm', $hours, $minutes);
        }

        return '-';
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'hadir' => 'success',
            'izin' => 'info',
            'sakit' => 'warning',
            'alpha' => 'danger',
            default => 'secondary'
        };
    }

    // Methods
    public function isPresent()
    {
        return $this->status === 'hadir';
    }

    public function isAbsent()
    {
        return $this->status === 'alpha';
    }

    public function isPermission()
    {
        return in_array($this->status, ['izin', 'sakit']);
    }
}