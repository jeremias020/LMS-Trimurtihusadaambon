<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Practical extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'guru_id',
        'subject_id',
        'judul',
        'deskripsi',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'durasi',
        'skill_level',
        'tools',
        'bahan',
        'instruksi',
        'keselamatan',
        'kelas_id',
        'max_score',
        'is_published',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_published' => 'boolean',
        'durasi' => 'integer',
        'max_score' => 'integer',
        'tools' => 'array',
        'bahan' => 'array',
        'instruksi' => 'array',
        'keselamatan' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
    
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function scores()
    {
        return $this->hasMany(PracticalScore::class);
    }

    // Relationship untuk mendapatkan siswa yang mengikuti praktikum
    public function siswa()
    {
        return $this->belongsToMany(User::class, 'practical_scores', 'practical_id', 'siswa_id')
                    ->where('users.role', 'siswa');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tanggal', '>=', now());
    }

    public function scopePast($query)
    {
        return $query->where('tanggal', '<', now());
    }

    public function scopeByGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId);
    }

    public function getStatusAttribute()
    {
        if (!$this->tanggal) {
            return 'draft';
        }

        $praktikumDate = \Carbon\Carbon::parse($this->tanggal);
        
        if ($praktikumDate->isPast()) {
            return 'completed';
        }

        return $this->is_published ? 'upcoming' : 'draft';
    }

    public function getParticipantCountAttribute()
    {
        return $this->scores()->distinct('siswa_id')->count('siswa_id');
    }

    public function getAverageScoreAttribute()
    {
        return $this->scores()->avg('score') ?? 0;
    }

    // Methods
    public function canBeScored()
    {
        if (!$this->tanggal) {
            return false;
        }
        
        $praktikumDate = \Carbon\Carbon::parse($this->tanggal);
        return $praktikumDate->isPast();
    }

    public function hasSiswaScore($siswaId)
    {
        return $this->scores()->where('siswa_id', $siswaId)->exists();
    }
}