<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenilaian extends Model
{
    use HasFactory;

    protected $table = 'detail_penilaian';

    protected $fillable = [
        'nilai_praktik_id',
        'kriteria_id',
        'skor',
        'catatan'
    ];

    /**
     * Relationship dengan nilai praktik
     */
    public function nilaiPraktik(): BelongsTo
    {
        return $this->belongsTo(NilaiPraktik::class);
    }

    /**
     * Relationship dengan kriteria penilaian
     */
    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(KriteriaPenilaian::class, 'kriteria_id');
    }

    /**
     * Get skor dalam persen (1-4 scale ke 0-100)
     */
    public function getSkorPersenAttribute()
    {
        return round(($this->skor / 4) * 100);
    }

    /**
     * Get score level description
     */
    public function getSkorLevelAttribute()
    {
        switch ($this->skor) {
            case 4: return 'Sangat Baik';
            case 3: return 'Baik';
            case 2: return 'Cukup';
            case 1: return 'Kurang';
            default: return 'Tidak Dinilai';
        }
    }

    /**
     * Get score badge color
     */
    public function getSkorBadgeAttribute()
    {
        $colors = [
            4 => 'success',
            3 => 'primary',
            2 => 'warning',
            1 => 'danger'
        ];
        
        return $colors[$this->skor] ?? 'secondary';
    }

    /**
     * Calculate weighted score berdasarkan bobot kriteria
     */
    public function getWeightedScoreAttribute()
    {
        return ($this->skor_persen * $this->kriteria->bobot);
    }
}