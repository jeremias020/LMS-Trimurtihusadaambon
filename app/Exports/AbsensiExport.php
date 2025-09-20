<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $attendances;
    protected $month;
    protected $year;

    public function __construct($attendances, $month, $year)
    {
        $this->attendances = $attendances;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return $this->attendances;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Mata Pelajaran',
            'Status',
            'Waktu Masuk',
            'Waktu Keluar',
            'Keterangan'
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->date->format('d M Y'),
            $attendance->subject->name ?? 'Tidak tersedia',
            $this->getStatusText($attendance->status),
            $attendance->status == 'hadir' ? $attendance->check_in : '-',
            $attendance->status == 'hadir' ? ($attendance->check_out ?? '-') : '-',
            $attendance->notes ?? '-'
        ];
    }

    private function getStatusText($status)
    {
        return match($status) {
            'hadir' => 'Hadir',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            default => 'Alpa'
        };
    }
}
