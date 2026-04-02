<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'assignment_submissions';

    protected $fillable = [
        'assignment_id',
        'student_id',
        'file_url',
        'submission_text',
        'score',
        'feedback',
        'submitted_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'submitted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Scopes
    public function scopeGraded($query)
    {
        return $query->whereNotNull('score');
    }

    public function scopeUngraded($query)
    {
        return $query->whereNull('score');
    }

    // ✅ Perbaikan: Sederhanakan scope late
    public function scopeLate($query)
    {
        return $query->whereHas('assignment', function($q) {
            $q->whereColumn('assignment_submissions.submitted_at', '>', 'assignments.deadline');
        });
    }

    // Accessors
    public function getIsLateAttribute()
    {
        return $this->submitted_at > $this->assignment->deadline;
    }

    public function getStatusAttribute()
    {
        if (is_null($this->score)) {
            return $this->is_late ? 'late_submission' : 'submitted';
        }
        
        return $this->score >= $this->assignment->max_score * 6 ? 'passed' : 'failed';
    }
}