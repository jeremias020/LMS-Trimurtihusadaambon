<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admins';

    protected $fillable = [
        'user_id',
        'address',
        'birth_date',
        'gender',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserCentral::class);
    }

    // Accessors
    public function getAgeAttribute()
    {
        return $this->birth_date ? now()->diffInYears($this->birth_date) : null;
    }

    public function getGenderDisplayAttribute()
    {
        return match($this->gender) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => $this->gender
        };
    }

    public function getPhoneAttribute()
    {
        return $this->user?->phone;
    }

    public function getPhotoAttribute()
    {
        return $this->user?->photo;
    }

    public function getPhotoUrlAttribute()
    {
        return $this->user?->photo_url;
    }

    public function getNameAttribute()
    {
        return $this->user?->name;
    }

    public function getEmailAttribute()
    {
        return $this->user?->email;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'tidak_aktif');
    }

    // Methods
    public function isActive()
    {
        return $this->status === 'aktif' && $this->user?->isActive();
    }

    public function getRoleAttribute()
    {
        return 'admin';
    }
}
