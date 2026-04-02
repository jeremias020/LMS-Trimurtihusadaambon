<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'major_id',
        'academic_year',
        'wallpaper'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Add accessor for backward compatibility
    public function getNamaAttribute()
    {
        return $this->name;
    }

    /**
     * Relasi ke model User (Guru)
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by'); // Gunakan created_by jika ada, atau hapus
    }

    /**
     * Relasi ke model Jurusan
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'major_id');
    }

    /**
     * Relasi ke siswa
     */
    public function students(): HasMany
    {
        // Return empty relationship karena tidak ada field kelas_id
        return $this->hasMany(User::class, 'id')->whereRaw('1=0');
    }

    /**
     * Alias untuk students() untuk konsistensi dengan konvensi Indonesian
     */
    public function siswa(): HasMany
    {
        return $this->students();
    }
    
    /**
     * Relasi ke semua users (siswa) di kelas ini
     */
    public function users(): HasMany
    {
        // Tidak ada field kelas_id di users table, return empty relationship
        return $this->hasMany(User::class);
    }

    // Note: jurusan relationship removed as major is stored as string
    // Note: waliKelas renamed to guru to match database schema

    /**
     * Accessor untuk nama kelas lengkap
     */
    public function getNamaLengkapAttribute(): string
    {
        return $this->name; // Gunakan field name yang ada
    }

    /**
     * Scope untuk kelas aktif
     */
    public function scopeAktif($query)
    {
        return $query; // Tidak ada field status di tabel classes, return all
    }

    /**
     * Scope untuk kelas berdasarkan grade
     */
    public function scopeByGrade($query, $grade)
    {
        return $query; // Tidak ada field grade di tabel classes
    }

    /**
     * Scope untuk kelas berdasarkan jurusan
     */
    public function scopeByMajor($query, $major)
    {
        return $query->where('major_id', $major); // Gunakan major_id yang ada
    }
}
