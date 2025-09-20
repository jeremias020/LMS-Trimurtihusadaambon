<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'created_by'
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'subject_id');
    }
    
    public function practicals()
    {
        return $this->hasMany(Practical::class, 'subject_id');
    }
}
