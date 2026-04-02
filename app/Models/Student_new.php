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
        'foto',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = ['age', 'gender_display', 'full_name'];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserCentral::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    // Accessors
    public function getAgeAttribute()
    {
        return $this->date_lahir ? now()->diffInYears($this->date_lahir) : null;
    }

    public function getGenderDisplayAttribute()
    {
        return match($this->jenis_kelamin) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => $this->jenis_kelamin
        };
    }

    public function getFullNameAttribute()
    {
        return $this->user?->name;
    }

    public function getEmailAttribute()
    {
        return $this->user?->email;
    }

    public function getUsernameAttribute()
    {
        return $this->user?->username;
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return $this->user?->photo_url;
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

    public function scopeByTahunAjaran($query, $tahun)
    {
        return $query->where('tahun_ajaran', $tahun);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Methods
    public function isActive()
    {
        return $this->status === 'aktif' && $this->user?->isActive();
    }

    public function getRoleAttribute()
    {
        return 'siswa';
    }

    // Relationships
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

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'material_downloads', 'siswa_id', 'material_id');
    }

    public function assignments()
    {
        return $this->belongsToMany(Assignment::class, 'assignment_submissions', 'siswa_id', 'assignment_id');
    }

    public function practicals()
    {
        return $this->belongsToMany(Practical::class, 'practical_scores', 'siswa_id', 'practical_id');
    }
}
