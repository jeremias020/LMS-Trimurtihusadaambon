<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Siswa;

class MaterialView extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'siswa_id',
        'view_date',
        'view_count',
        'last_viewed_at',
    ];

    protected $casts = [
        'view_date' => 'date',
        'view_count' => 'integer',
        'last_viewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'user_id');
    }

    // Scopes
    public function scopeByMaterial($query, $materialId)
    {
        return $query->where('material_id', $materialId);
    }

    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('view_date', $date);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('last_viewed_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getFormattedLastViewedAttribute()
    {
        return $this->last_viewed_at ? $this->last_viewed_at->format('d M Y, H:i') : 'Never';
    }

    public function getViewDurationAttribute()
    {
        if (!$this->last_viewed_at) {
            return 'Never viewed';
        }

        return $this->last_viewed_at->diffForHumans();
    }
}
