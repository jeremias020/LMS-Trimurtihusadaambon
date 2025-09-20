<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MaterialExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    protected $materials;

    public function __construct($materials)
    {
        $this->materials = $materials;
    }

    public function collection()
    {
        return $this->materials;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Judul Materi',
            'Deskripsi',
            'Mata Pelajaran',
            'Kelas',
            'Guru',
            'Tanggal Dibuat',
            'Jumlah Unduhan',
            'Status',
            'Link Detail'
        ];
    }

    public function map($material): array
    {
        return [
            $material->id,
            $material->title,
            $material->description,
            $material->subject ?? 'Tidak tersedia',
            $material->class_level ?? 'Tidak tersedia',
            optional($material->teacher)->name ?? 'Tidak tersedia',
            $material->created_at->format('Y-m-d H:i:s'),
            $material->downloads_count,
            $this->getStatus($material),
            url(route('siswa.materials.show', $material->id))
        ];
    }

    private function getStatus($material)
    {
        if ($material->created_at->diffInDays(now()) <= 7) {
            return 'Baru';
        }
        return 'Tersedia';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '007BFF']],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}