<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataPelajaran extends Model
{
    use SoftDeletes;

    protected $table = 'subjects';

    protected $fillable = [
        'name',
        'code', 
        'description',
        'guru_id',
        'kelas_id',
        'sks',
        'type',
        'color',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sks' => 'integer',
        'order' => 'integer'
    ];

    public function jurusan()
    {
        return $this->belongsToMany(Jurusan::class, 'jurusan_mata_pelajaran');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTeori($query)
    {
        return $query->where('type', 'teori');
    }

    public function scopePraktikum($query)
    {
        return $query->where('type', 'praktikum');
    }

    public function scopeCampuran($query)
    {
        return $query->where('type', 'campuran');
    }

    public function scopeUmum($query)
    {
        return $query->where('type', 'teori'); // Assuming umum = teori
    }

    public function scopeKejuruan($query)
    {
        return $query->where('type', 'praktikum'); // Assuming kejuruan = praktikum
    }
}
