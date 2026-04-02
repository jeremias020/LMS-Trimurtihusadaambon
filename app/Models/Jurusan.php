<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusans';

    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relationship dengan model Kelas
     */
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'jurusan_id');
    }

    /**
     * Relationship dengan model User (siswa)
     */
    public function siswa(): HasMany
    {
        return $this->hasMany(Student::class, 'jurusan_id')->where('is_active', true);
    }

    /**
     * Get total siswa dalam jurusan
     */
    public function getTotalSiswaAttribute()
    {
        return $this->siswa()->count();
    }

    /**
     * Get total kelas dalam jurusan
     */
    public function getTotalKelasAttribute()
    {
        return $this->kelas()->count();
    }

    /**
     * Scope untuk jurusan aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Get mata pelajaran as formatted string
     */
    public function getMataPelajaranStringAttribute()
    {
        if (is_array($this->mata_pelajaran)) {
            return implode(', ', $this->mata_pelajaran);
        }
        return '';
    }

    /**
     * Static method untuk get jurusan kesehatan default
     */
    public static function getDefaultJurusan()
    {
        return [
            [
                'nama' => 'Keperawatan',
                'kode' => 'KPR',
                'deskripsi' => 'Program Keahlian Keperawatan',
                'mata_pelajaran' => [
                    'Anatomi Fisiologi',
                    'Patologi',
                    'Farmakologi',
                    'Keperawatan Dasar',
                    'Keperawatan Medikal Bedah',
                    'Keperawatan Anak',
                    'Keperawatan Maternitas',
                    'Keperawatan Jiwa',
                    'Keperawatan Komunitas'
                ]
            ],
            [
                'nama' => 'Farmasi',
                'kode' => 'FAR',
                'deskripsi' => 'Program Keahlian Farmasi Klinis dan Komunitas',
                'mata_pelajaran' => [
                    'Kimia Farmasi',
                    'Farmakologi',
                    'Farmasetika',
                    'Farmakognosi',
                    'Farmasi Klinik',
                    'Managemen Farmasi',
                    'Kimia Analisis',
                    'Biologi Farmasi'
                ]
            ],
            [
                'nama' => 'Teknologi Laboratorium Medik',
                'kode' => 'TLM',
                'deskripsi' => 'Program Keahlian Analis Kesehatan',
                'mata_pelajaran' => [
                    'Hematologi',
                    'Kimia Klinik',
                    'Mikrobiologi',
                    'Parasitologi',
                    'Imunologi',
                    'Urinalisis',
                    'Histopatologi',
                    'Toksikologi'
                ]
            ]
        ];
    }

    /**
     * Seed default jurusan
     */
    public static function seedDefault()
    {
        $defaultJurusan = self::getDefaultJurusan();
        
        foreach ($defaultJurusan as $jurusan) {
            self::updateOrCreate(
                ['kode' => $jurusan['kode']],
                $jurusan
            );
        }
    }
}