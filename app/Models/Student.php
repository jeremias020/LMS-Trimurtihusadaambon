<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nis_nip',
        'phone',
        'avatar',
        'is_active',
        'remember_token',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = ['photo_url', 'age', 'gender_display', 'full_name'];

    // Accessors
    public function getPhotoUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function getAgeAttribute()
    {
        return null; // Tidak ada field tanggal_lahir di tabel users
    }

    public function getGenderDisplayAttribute()
    {
        return null; // Tidak ada field jenis_kelamin di tabel users
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByKelas($query, $kelasId)
    {
        return $query; // Tidak ada relationship ke class_students
    }

    public function scopeByMajor($query, $major)
    {
        return $query; // Tidak ada field major di tabel users
    }

    public function scopeByTahunAjaran($query, $tahun)
    {
        return $query; // Tidak ada field tahun_ajaran di tabel users
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Methods
    public function isActive()
    {
        return $this->is_active === true;
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
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
