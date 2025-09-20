<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KriteriaPenilaian extends Model
{
    use HasFactory;

    protected $table = 'kriteria_penilaian';

    protected $fillable = [
        'nama',
        'kategori',
        'bobot',
        'deskripsi',
        'sop_checklist',
        'mata_praktik',
        'tingkat_kelas',
        'status'
    ];

    protected $casts = [
        'sop_checklist' => 'array',
        'bobot' => 'decimal:2',
        'status' => 'boolean'
    ];

    protected $attributes = [
        'status' => true,
        'sop_checklist' => '[]'
    ];

    // Kategori penilaian yang tersedia
    const KATEGORI_PERSIAPAN = 'persiapan';
    const KATEGORI_PELAKSANAAN = 'pelaksanaan';
    const KATEGORI_HASIL = 'hasil';
    const KATEGORI_SIKAP = 'sikap';

    // Tingkat kelas
    const TINGKAT_X = 'X';
    const TINGKAT_XI = 'XI';
    const TINGKAT_XII = 'XII';

    /**
     * Relationship dengan NilaiPraktik
     */
    public function nilaiPraktik(): HasMany
    {
        return $this->hasMany(DetailPenilaian::class, 'kriteria_id');
    }

    /**
     * Scope untuk kriteria aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope berdasarkan kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope berdasarkan mata praktik
     */
    public function scopeByMataPraktik($query, $mataPraktik)
    {
        return $query->where('mata_praktik', $mataPraktik);
    }

    /**
     * Scope berdasarkan tingkat kelas
     */
    public function scopeByTingkatKelas($query, $tingkat)
    {
        return $query->where('tingkat_kelas', $tingkat);
    }

    /**
     * Get list kategori yang tersedia
     */
    public static function getKategoriList()
    {
        return [
            self::KATEGORI_PERSIAPAN => 'Persiapan',
            self::KATEGORI_PELAKSANAAN => 'Pelaksanaan',
            self::KATEGORI_HASIL => 'Hasil',
            self::KATEGORI_SIKAP => 'Sikap Profesional'
        ];
    }

    /**
     * Get list tingkat kelas
     */
    public static function getTingkatKelasList()
    {
        return [
            self::TINGKAT_X => 'Kelas X',
            self::TINGKAT_XI => 'Kelas XI',
            self::TINGKAT_XII => 'Kelas XII'
        ];
    }

    /**
     * Get bobot dalam persen
     */
    public function getBobotPersenAttribute()
    {
        return ($this->bobot * 100) . '%';
    }

    /**
     * Get SOP checklist count
     */
    public function getJumlahChecklistAttribute()
    {
        if (is_array($this->sop_checklist)) {
            return count($this->sop_checklist);
        }
        return 0;
    }

    /**
     * Default kriteria untuk praktik keperawatan
     */
    public static function getDefaultKriteriaKeperawatan()
    {
        return [
            [
                'nama' => 'Persiapan Alat dan Bahan',
                'kategori' => self::KATEGORI_PERSIAPAN,
                'bobot' => 0.20,
                'deskripsi' => 'Kelengkapan dan kesesuaian alat serta bahan yang disiapkan',
                'mata_praktik' => 'Keperawatan Dasar',
                'tingkat_kelas' => self::TINGKAT_X,
                'sop_checklist' => [
                    'Menyiapkan alat sesuai prosedur',
                    'Memeriksa kelengkapan alat',
                    'Memastikan sterilitas alat',
                    'Menyiapkan bahan habis pakai',
                    'Mengatur posisi alat dengan ergonomis'
                ]
            ],
            [
                'nama' => 'Pelaksanaan Tindakan Keperawatan',
                'kategori' => self::KATEGORI_PELAKSANAAN,
                'bobot' => 0.40,
                'deskripsi' => 'Ketepatan dan keterampilan dalam melaksanakan tindakan',
                'mata_praktik' => 'Keperawatan Dasar',
                'tingkat_kelas' => self::TINGKAT_X,
                'sop_checklist' => [
                    'Melakukan cuci tangan sebelum tindakan',
                    'Menggunakan APD sesuai prosedur',
                    'Melaksanakan tindakan sesuai SOP',
                    'Mengaplikasikan prinsip steril/aseptik',
                    'Menunjukkan keterampilan yang tepat'
                ]
            ],
            [
                'nama' => 'Hasil dan Evaluasi',
                'kategori' => self::KATEGORI_HASIL,
                'bobot' => 0.25,
                'deskripsi' => 'Kualitas hasil tindakan dan kemampuan evaluasi',
                'mata_praktik' => 'Keperawatan Dasar',
                'tingkat_kelas' => self::TINGKAT_X,
                'sop_checklist' => [
                    'Hasil tindakan sesuai standar',
                    'Melakukan evaluasi hasil',
                    'Mendokumentasikan dengan benar',
                    'Memberikan edukasi kepada pasien/keluarga',
                    'Melakukan tindak lanjut yang tepat'
                ]
            ],
            [
                'nama' => 'Sikap Profesional',
                'kategori' => self::KATEGORI_SIKAP,
                'bobot' => 0.15,
                'deskripsi' => 'Sikap dan perilaku profesional selama praktik',
                'mata_praktik' => 'Keperawatan Dasar',
                'tingkat_kelas' => self::TINGKAT_X,
                'sop_checklist' => [
                    'Berkomunikasi dengan baik',
                    'Menunjukkan empati dan caring',
                    'Menjaga privacy dan confidentiality',
                    'Bekerja sama dalam tim',
                    'Menunjukkan tanggung jawab profesional'
                ]
            ]
        ];
    }

    /**
     * Default kriteria untuk praktik farmasi
     */
    public static function getDefaultKriteriaFarmasi()
    {
        return [
            [
                'nama' => 'Persiapan dan Identifikasi',
                'kategori' => self::KATEGORI_PERSIAPAN,
                'bobot' => 0.25,
                'deskripsi' => 'Persiapan workspace dan identifikasi obat/bahan',
                'mata_praktik' => 'Farmasetika',
                'tingkat_kelas' => self::TINGKAT_XI,
                'sop_checklist' => [
                    'Menyiapkan area kerja yang bersih',
                    'Mengidentifikasi obat/bahan dengan benar',
                    'Memeriksa tanggal kadaluwarsa',
                    'Menyiapkan alat timbang dan ukur',
                    'Menggunakan APD yang sesuai'
                ]
            ],
            [
                'nama' => 'Teknik Pembuatan/Peracikan',
                'kategori' => self::KATEGORI_PELAKSANAAN,
                'bobot' => 0.35,
                'deskripsi' => 'Keterampilan dalam pembuatan/peracikan obat',
                'mata_praktik' => 'Farmasetika',
                'tingkat_kelas' => self::TINGKAT_XI,
                'sop_checklist' => [
                    'Menerapkan teknik aseptik',
                    'Melakukan penimbangan dengan akurat',
                    'Menggunakan teknik pencampuran yang benar',
                    'Mengikuti formula yang ditetapkan',
                    'Menjaga stabilitas sediaan'
                ]
            ],
            [
                'nama' => 'Kontrol Kualitas dan Kemasan',
                'kategori' => self::KATEGORI_HASIL,
                'bobot' => 0.25,
                'deskripsi' => 'Pemeriksaan kualitas dan pengemasan hasil',
                'mata_praktik' => 'Farmasetika',
                'tingkat_kelas' => self::TINGKAT_XI,
                'sop_checklist' => [
                    'Memeriksa organoleptik sediaan',
                    'Melakukan uji kualitas dasar',
                    'Mengemas dengan benar',
                    'Membuat etiket yang sesuai',
                    'Menyimpan sediaan dengan tepat'
                ]
            ],
            [
                'nama' => 'Etika dan Keselamatan Kerja',
                'kategori' => self::KATEGORI_SIKAP,
                'bobot' => 0.15,
                'deskripsi' => 'Penerapan etika profesi dan K3',
                'mata_praktik' => 'Farmasetika',
                'tingkat_kelas' => self::TINGKAT_XI,
                'sop_checklist' => [
                    'Mematuhi prinsip K3 laboratorium',
                    'Menerapkan Good Manufacturing Practice',
                    'Menjaga kerahasiaan resep',
                    'Bekerja dengan teliti dan hati-hati',
                    'Mengelola limbah dengan benar'
                ]
            ]
        ];
    }

    /**
     * Seed default criteria
     */
    public static function seedDefault()
    {
        $kriteriaKeperawatan = self::getDefaultKriteriaKeperawatan();
        $kriteriaFarmasi = self::getDefaultKriteriaFarmasi();
        
        $allKriteria = array_merge($kriteriaKeperawatan, $kriteriaFarmasi);
        
        foreach ($allKriteria as $kriteria) {
            self::updateOrCreate([
                'nama' => $kriteria['nama'],
                'mata_praktik' => $kriteria['mata_praktik'],
                'tingkat_kelas' => $kriteria['tingkat_kelas']
            ], $kriteria);
        }
    }
}