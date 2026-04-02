<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guru extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'gurus';

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
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
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'tanggal_lahir' => 'date',
        'tahun_mulai_kerja' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = ['photo_url', 'age', 'gender_display', 'work_duration'];

    // Accessors
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeBySubject($query, $subject)
    {
        return $query->where('mata_pelajaran', 'like', '%' . $subject . '%');
    }

    // Methods
    public function isActive()
    {
        return $this->status === 'aktif';
    }

    public function getRoleAttribute()
    {
        return 'guru';
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

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
