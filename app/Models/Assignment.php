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
        'class_subject_id',
        'title',
        'description',
        'instructions',
        'file_url',
        'due_date',
        'max_score',
        'allow_late',
        'is_published',
    ];

    protected $casts = [
        'due_date' => 'datetime',
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
    
    // Manual relationship for class_subject
    public function getClassSubject()
    {
        return \DB::table('class_subjects')
            ->join('subjects', 'class_subjects.subject_id', '=', 'subjects.id')
            ->join('classes', 'class_subjects.class_id', '=', 'classes.id')
            ->where('class_subjects.id', $this->class_subject_id)
            ->select(
                'class_subjects.id',
                'subjects.name as subject_name',
                'subjects.id as subject_id',
                'classes.name as class_name',
                'classes.id as class_id'
            )
            ->first();
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

    // Accessors for backward compatibility
    public function getDeadlineAttribute($value)
    {
        return $this->due_date;
    }

    public function setDeadlineAttribute($value)
    {
        $this->attributes['due_date'] = $value;
    }

    // Scopes
    public function scopePublished($query)
    {
        // Assignments don't have published status, all are considered published
        return $query;
    }

    public function scopeActive($query)
    {
        return $query->where('due_date', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('due_date', '<=', now());
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