<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'kelas_id',
        'major',
        'tahun_ajaran',
        'nama_ortu',
        'no_telepon_ortu',
        'golongan_darah',
        'riwayat_penyakit',
        'alergi',
        'info_kesehatan',
        'status'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'siswa_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'siswa_id');
    }

    public function assignmentSubmissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'siswa_id');
    }

    public function practicalScores()
    {
        return $this->hasMany(PracticalScore::class, 'siswa_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    public function scopeByMajor($query, $major)
    {
        return $query->where('major', $major);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->user ? $this->user->name : $this->name;
    }

    public function getAgeAttribute()
    {
        return $this->tanggal_lahir ? now()->diffInYears($this->tanggal_lahir) : null;
    }
}
