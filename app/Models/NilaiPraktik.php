<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NilaiPraktik extends Model
{
    use HasFactory;

    protected $table = 'nilai_praktik';

    protected $fillable = [
        'siswa_id',
        'guru_id',
        'mata_praktik',
        'tanggal_praktik',
        'total_nilai',
        'grade',
        'feedback_otomatis',
        'catatan_guru',
        'status'
    ];

    protected $casts = [
        'tanggal_praktik' => 'date',
        'total_nilai' => 'decimal:2'
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_FINAL = 'final';

    const GRADE_A = 'A';
    const GRADE_B = 'B';
    const GRADE_C = 'C';
    const GRADE_D = 'D';
    const GRADE_E = 'E';

    protected $attributes = [
        'status' => self::STATUS_DRAFT
    ];

    /**
     * Relationship dengan siswa
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    /**
     * Relationship dengan guru
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    /**
     * Relationship dengan detail penilaian per kriteria
     */
    public function detailPenilaian(): HasMany
    {
        return $this->hasMany(DetailPenilaian::class);
    }

    /**
     * Scope untuk nilai final
     */
    public function scopeFinal($query)
    {
        return $query->where('status', self::STATUS_FINAL);
    }

    /**
     * Scope berdasarkan siswa
     */
    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    /**
     * Scope berdasarkan guru
     */
    public function scopeByGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId);
    }

    /**
     * Scope berdasarkan mata praktik
     */
    public function scopeByMataPraktik($query, $mataPraktik)
    {
        return $query->where('mata_praktik', $mataPraktik);
    }

    /**
     * Get grade list
     */
    public static function getGradeList()
    {
        return [
            self::GRADE_A => 'A (90-100)',
            self::GRADE_B => 'B (80-89)',
            self::GRADE_C => 'C (70-79)',
            self::GRADE_D => 'D (60-69)',
            self::GRADE_E => 'E (<60)'
        ];
    }

    /**
     * Get status list
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_FINAL => 'Final'
        ];
    }

    /**
     * Determine grade berdasarkan total nilai
     */
    public static function determineGrade($totalNilai)
    {
        if ($totalNilai >= 90) return self::GRADE_A;
        if ($totalNilai >= 80) return self::GRADE_B;
        if ($totalNilai >= 70) return self::GRADE_C;
        if ($totalNilai >= 60) return self::GRADE_D;
        return self::GRADE_E;
    }

    /**
     * Calculate total nilai berdasarkan detail penilaian
     */
    public function calculateTotalNilai()
    {
        $totalNilai = 0;
        
        foreach ($this->detailPenilaian as $detail) {
            $kriteria = $detail->kriteria;
            $skorNormalized = ($detail->skor / 4) * 100; // Convert 1-4 scale to 0-100
            $nilaiTerbobot = $skorNormalized * $kriteria->bobot;
            $totalNilai += $nilaiTerbobot;
        }
        
        return round($totalNilai, 2);
    }

    /**
     * Generate auto feedback berdasarkan nilai per kriteria
     */
    public function generateAutoFeedback()
    {
        $feedback = "🎯 **Nilai Praktik: {$this->total_nilai}/100 (Grade: {$this->grade})**\n\n";
        $feedback .= "📊 **Detail Penilaian:**\n";
        
        foreach ($this->detailPenilaian as $detail) {
            $kriteria = $detail->kriteria;
            $skorLevel = $this->getScoreLevel($detail->skor);
            $skorPersen = round(($detail->skor / 4) * 100);
            
            $feedback .= "• **{$kriteria->nama}**: {$detail->skor}/4 ({$skorPersen}%) - {$skorLevel}\n";
            
            if ($detail->catatan) {
                $feedback .= "  📝 *{$detail->catatan}*\n";
            }
        }
        
        $feedback .= "\n💡 **Saran Perbaikan:**\n";
        $feedback .= $this->generateImprovementSuggestions();
        
        $feedback .= "\n🏆 **Kesimpulan:**\n";
        $feedback .= $this->generateOverallConclusion();
        
        return $feedback;
    }

    /**
     * Get score level description
     */
    private function getScoreLevel($skor)
    {
        switch ($skor) {
            case 4: return 'Sangat Baik';
            case 3: return 'Baik';
            case 2: return 'Cukup';
            case 1: return 'Kurang';
            default: return 'Tidak Dinilai';
        }
    }

    /**
     * Generate improvement suggestions
     */
    private function generateImprovementSuggestions()
    {
        $suggestions = [];
        
        foreach ($this->detailPenilaian as $detail) {
            $kriteria = $detail->kriteria;
            
            if ($detail->skor < 3) {
                switch ($kriteria->kategori) {
                    case 'persiapan':
                        $suggestions[] = "• Perbaiki persiapan alat dan bahan untuk {$kriteria->nama}";
                        break;
                    case 'pelaksanaan':
                        $suggestions[] = "• Tingkatkan keterampilan teknis dalam {$kriteria->nama}";
                        break;
                    case 'hasil':
                        $suggestions[] = "• Perhatikan kualitas hasil dan dokumentasi pada {$kriteria->nama}";
                        break;
                    case 'sikap':
                        $suggestions[] = "• Kembangkan sikap profesional dalam {$kriteria->nama}";
                        break;
                }
            }
        }
        
        if (empty($suggestions)) {
            return "Pertahankan kinerja yang sudah baik dan terus tingkatkan kualitas praktik.";
        }
        
        return implode("\n", $suggestions);
    }

    /**
     * Generate overall conclusion
     */
    private function generateOverallConclusion()
    {
        $grade = $this->grade;
        
        switch ($grade) {
            case self::GRADE_A:
                return "Excellent! Anda menunjukkan kemampuan praktik yang sangat baik. Pertahankan standar ini.";
            case self::GRADE_B:
                return "Good job! Kemampuan praktik Anda sudah baik. Sedikit perbaikan akan membuat Anda sempurna.";
            case self::GRADE_C:
                return "Cukup baik. Masih ada beberapa area yang perlu diperbaiki. Terus berlatih dan konsultasi dengan pembimbing.";
            case self::GRADE_D:
                return "Perlu perbaikan. Fokus pada area yang masih lemah dan lakukan latihan tambahan.";
            case self::GRADE_E:
                return "Perlu perbaikan menyeluruh. Disarankan untuk latihan intensif dan bimbingan khusus.";
            default:
                return "Lanjutkan usaha dan terus berlatih untuk meningkatkan kemampuan praktik.";
        }
    }

    /**
     * Get grade badge color
     */
    public function getGradeBadgeAttribute()
    {
        $colors = [
            self::GRADE_A => 'success',
            self::GRADE_B => 'primary',
            self::GRADE_C => 'warning',
            self::GRADE_D => 'danger',
            self::GRADE_E => 'dark'
        ];
        
        return $colors[$this->grade] ?? 'secondary';
    }

    /**
     * Check if nilai is passing
     */
    public function isPassing()
    {
        return $this->total_nilai >= 70; // Minimal C
    }

    /**
     * Get formatted nilai with status
     */
    public function getNilaiFormattedAttribute()
    {
        $status = $this->isPassing() ? 'LULUS' : 'TIDAK LULUS';
        return "{$this->total_nilai} ({$this->grade}) - {$status}";
    }

    /**
     * Event handlers
     */
    protected static function booted()
    {
        // Auto calculate dan generate feedback ketika status berubah ke final
        static::updating(function ($nilai) {
            if ($nilai->isDirty('status') && $nilai->status === self::STATUS_FINAL) {
                $nilai->total_nilai = $nilai->calculateTotalNilai();
                $nilai->grade = self::determineGrade($nilai->total_nilai);
                $nilai->feedback_otomatis = $nilai->generateAutoFeedback();
            }
        });
    }
}