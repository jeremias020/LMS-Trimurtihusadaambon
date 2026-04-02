<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus';

    protected $fillable = [
        'user_id',
        'nip',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'email_pribadi',
        'mata_pelajaran',
        'pendidikan_terakhir',
        'jurusan_pendidikan',
        'tahun_mulai_kerja',
        'photo',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tahun_mulai_kerja' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = ['age', 'gender_display', 'work_duration'];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserCentral::class);
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

    public function getWorkDurationAttribute()
    {
        return $this->tahun_mulai_kerja ? now()->year - $this->tahun_mulai_kerja : null;
    }

    public function getNameAttribute()
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
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return $this->user?->photo_url;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'tidak_aktif');
    }

    public function scopeBySubject($query, $subject)
    {
        return $query->where('mata_pelajaran', 'like', '%' . $subject . '%');
    }

    // Methods
    public function isActive()
    {
        return $this->status === 'aktif' && $this->user?->isActive();
    }

    public function getRoleAttribute()
    {
        return 'guru';
    }

    // Relationships
    public function materials()
    {
        return $this->hasMany(Material::class, 'guru_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'guru_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'guru_id');
    }

    public function practicals()
    {
        return $this->hasMany(Practical::class, 'guru_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'guru_id');
    }
}
