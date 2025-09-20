<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Criteria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'criteria';

    protected $fillable = [
        'name',
        'description',
        'weight',
        'max_score',
        'subject_id',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'float',
        'max_score' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function practicalScores()
    {
        return $this->hasMany(PracticalScore::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByWeight($query, $order = 'desc')
    {
        return $query->orderBy('weight', $order);
    }

    // Accessors
    public function getWeightPercentageAttribute()
    {
        return $this->weight * 100;
    }

    // Methods
    public function deactivate()
    {
        $this->update(['is_active' => false]);
    }

    public function activate()
    {
        $this->update(['is_active' => true]);
    }

    public function canBeDeleted()
    {
        return $this->practicalScores()->count() === 0;
    }
}