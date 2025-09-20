<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'name',
        'code',
        'grade',
        'major',
        'description',
        'guru_id',
        'capacity',
        'academic_year',
        'status'
    ];

    /**
     * Relasi ke model User (Guru)
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    /**
     * Relasi ke siswa
     */
    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'kelas_id')->where('role', 'siswa');
    }
    
    /**
     * Relasi ke semua users (siswa) di kelas ini
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'kelas_id');
    }

    /**
     * Accessor untuk nama kelas lengkap
     */
    public function getNamaLengkapAttribute(): string
    {
        return "Kelas {$this->grade} {$this->major} {$this->name}";
    }

    /**
     * Scope untuk kelas aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope untuk kelas berdasarkan grade
     */
    public function scopeByGrade($query, $grade)
    {
        return $query->where('grade', $grade);
    }

    /**
     * Scope untuk kelas berdasarkan jurusan
     */
    public function scopeByMajor($query, $major)
    {
        return $query->where('major', $major);
    }
}
