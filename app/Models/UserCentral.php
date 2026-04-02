<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserCentral extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users_central';

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'role',
        'phone',
        'photo',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = ['photo_url', 'role_display'];

    // Accessors
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function getRoleDisplayAttribute()
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'guru' => 'Guru',
            'siswa' => 'Siswa',
            default => $this->role
        };
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeGuru($query)
    {
        return $query->where('role', 'guru');
    }

    public function scopeSiswa($query)
    {
        return $query->where('role', 'siswa');
    }

    // Methods
    public function isActive()
    {
        return $this->is_active;
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isGuru()
    {
        return $this->role === 'guru';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    // Relationships
    public function adminProfile(): HasOne
    {
        return $this->hasOne(Admin::class, 'user_id');
    }

    public function guruProfile(): HasOne
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    public function siswaProfile(): HasOne
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    public function getProfileAttribute()
    {
        return match($this->role) {
            'admin' => $this->adminProfile,
            'guru' => $this->guruProfile,
            'siswa' => $this->siswaProfile,
            default => null
        };
    }
}