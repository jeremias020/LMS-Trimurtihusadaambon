<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'guru_id',
        'subject_id',
        'kelas_id',
        'title',
        'description',
        'instructions',
        'file',
        'file_path',
        'file_size',
        'file_type',
        'deadline',
        'max_score',
        'is_published',
        'class', // Keep for backward compatibility
        'allow_late',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'is_published' => 'boolean',
        'max_score' => 'integer',
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
        return $this->belongsTo(Kelas::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    // ✅ Perbaikan: Gunakan hasManyThrough untuk relationship siswa
    public function siswa()
    {
        return $this->hasManyThrough(
            User::class,
            AssignmentSubmission::class,
            'assignment_id',
            'id',
            'id',
            'siswa_id'
        );
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeActive($query)
    {
        return $query->where('deadline', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('deadline', '<=', now());
    }

    public function scopeByGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId);
    }

    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    // Accessors
    public function getFileUrlAttribute()
    {
        return $this->file ? asset('storage/assignments/' . $this->file) : null;
    }

    public function getStatusAttribute()
    {
        if (!$this->deadline) {
            return 'draft';
        }

        if ($this->deadline->isPast()) {
            return 'expired';
        }

        return $this->is_published ? 'active' : 'draft';
    }

    public function getSubmissionCountAttribute()
    {
        return $this->submissions()->count();
    }

    public function getAverageScoreAttribute()
    {
        return $this->submissions()->whereNotNull('score')->avg('score') ?? 0;
    }

    // Methods
    public function canBeSubmitted()
    {
        return $this->is_published && $this->deadline && $this->deadline->isFuture();
    }

    public function hasUserSubmission($userId)
    {
        return $this->submissions()->where('siswa_id', $userId)->exists();
    }
}