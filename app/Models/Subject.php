<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'major_id',
        'jurusan_id',
        'guru_id',
        'kelas_id',
        'type',
        'description',
        'sks'
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'major_id');
    }

    // Add accessor for backward compatibility
    public function getNamaAttribute()
    {
        return $this->attributes['name'] ?? $this->name;
    }
    
    // Add mutator for backward compatibility  
    public function setNamaAttribute($value)
    {
        $this->attributes['name'] = $value;
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'subject_id');
    }
    
    public function practicals()
    {
        return $this->hasMany(Practical::class, 'subject_id');
    }
    
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'subject_id');
    }
    
    public function examSchedules()
    {
        return $this->hasMany(ExamSchedule::class, 'subject_id');
    }

    /**
     * Relationship dengan Kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }}
