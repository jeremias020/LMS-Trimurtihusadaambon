<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus';

    protected $fillable = [
        'user_id',
        'nip',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'email',
        'mata_pelajaran',
        'pendidikan_terakhir',
        'foto',
        'status'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'guru_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'guru_id');
    }

    public function practicals()
    {
        return $this->hasMany(Practical::class, 'guru_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'guru_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeBySubject($query, $subject)
    {
        return $query->where('mata_pelajaran', $subject);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->user ? $this->user->name : $this->nama;
    }

    public function getAgeAttribute()
    {
        return $this->tanggal_lahir ? now()->diffInYears($this->tanggal_lahir) : null;
    }
}
