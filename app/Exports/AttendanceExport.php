<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AttendanceExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles,
    WithTitle,
    WithCustomStartCell,
    WithColumnFormatting,
    WithEvents
{
    protected $attendances;
    protected $stats;
    protected $classSummary;

    public function __construct($attendances, $stats, $classSummary)
    {
        $this->attendances = $attendances;
        $this->stats = $stats;
        $this->classSummary = $classSummary;
    }

    public function collection()
    {
        return $this->attendances;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Lengkap',
            'Email',
            'NIS/NIP',
            'Kelas',
            'Role',
            'Mata Pelajaran',
            'Tanggal',
            'Hari',
            'Jam',
            'Status Kehadiran',
            'Status Waktu',
            'Keterangan',
            'Lokasi'
        ];
    }

    public function map($attendance): array
    {
        $userName = $attendance->user->name ?? '-';
        $userEmail = $attendance->user->email ?? '-';
        $userNisNip = $attendance->user->nis_nip ?? '-';
        $userClass = $attendance->user->class ?? '-';
        $userRole = $attendance->user->role ?? '-';
        $subjectName = $attendance->subject->name ?? '-';

        return [
            $attendance->id,
            $userName,
            $userEmail,
            $userNisNip,
            $userClass,
            $this->getRoleText($userRole),
            $subjectName,
            $attendance->date->format('d/m/Y'),
            $this->getIndonesianDay($attendance->date->format('l')),
            $attendance->time ?? '-',
            $this->getAttendanceStatusText($attendance->status),
            $this->getTimeStatus($attendance),
            $attendance->notes ?? '-',
            $attendance->location ?? '-'
        ];
    }

    public function title(): string
    {
        return 'Laporan Kehadiran - SMK Kesehatan Trimurti Husada Ambon';
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->setAutoFilter('A1:N1');
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header row
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('1')->getFill()->getStartColor()->setRGB('2C5AA0');
        $sheet->getStyle('1')->getFont()->getColor()->setRGB('FFFFFF');

        // Style header cells
        $sheet->getStyle('A1:N1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:N1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Style data rows
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A2:N' . $highestRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Add borders
        $sheet->getStyle('A1:N' . $highestRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Add summary section
        $this->addSummarySection($sheet);

        // Apply conditional formatting for status columns
        $this->applyConditionalFormatting($sheet, $highestRow);

        // Freeze pane
        $sheet->freezePane('A2');
    }

    private function applyConditionalFormatting(Worksheet $sheet, $highestRow)
    {
        $sheet->getStyle('K2:K' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L2:L' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    private function addSummarySection(Worksheet $sheet)
    {
        $startRow = $sheet->getHighestRow() + 3;

        // Summary title
        $sheet->setCellValue('A' . $startRow, 'RINGKASAN LAPORAN KEHADIRAN');
        $sheet->mergeCells('A' . $startRow . ':N' . $startRow);
        $sheet->getStyle('A' . $startRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $startRow)->getFont()->setSize(14);
        $sheet->getStyle('A' . $startRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Date range
        $startRow++;
        $sheet->setCellValue('A' . $startRow, 'Periode: ' . Carbon::now()->translatedFormat('d F Y'));
        $sheet->mergeCells('A' . $startRow . ':N' . $startRow);
        $sheet->getStyle('A' . $startRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Total statistics
        $startRow++;
        $sheet->setCellValue('A' . $startRow, 'Total Pengguna:');
        $sheet->setCellValue('B' . $startRow, $this->stats['total_users'] ?? 0);

        $startRow++;
        $sheet->setCellValue('A' . $startRow, 'Total Hadir:');
        $sheet->setCellValue('B' . $startRow, $this->stats['present_count'] ?? 0);

        $startRow++;
        $sheet->setCellValue('A' . $startRow, 'Total Tidak Hadir:');
        $sheet->setCellValue('B' . $startRow, $this->stats['absent_count'] ?? 0);

        $startRow++;
        $sheet->setCellValue('A' . $startRow, 'Total Terlambat:');
        $sheet->setCellValue('B' . $startRow, $this->stats['late_count'] ?? 0);

        $startRow++;
        $sheet->setCellValue('A' . $startRow, 'Total Izin:');
        $sheet->setCellValue('B' . $startRow, $this->stats['excused_count'] ?? 0);

        $startRow++;
        $sheet->setCellValue('A' . $startRow, 'Tingkat Kehadiran:');
        $sheet->setCellValue('B' . $startRow, ($this->stats['attendance_rate'] ?? 0) . '%');

        // Class summary header
        $startRow += 2;
        $sheet->setCellValue('A' . $startRow, 'RINGKASAN PER KELAS');
        $sheet->mergeCells('A' . $startRow . ':N' . $startRow);
        $sheet->getStyle('A' . $startRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $startRow)->getFont()->setSize(12);
        $sheet->getStyle('A' . $startRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Class summary table
        $startRow++;
        $sheet->setCellValue('A' . $startRow, 'Kelas');
        $sheet->setCellValue('B' . $startRow, 'Total Siswa');
        $sheet->setCellValue('C' . $startRow, 'Hadir');
        $sheet->setCellValue('D' . $startRow, 'Tidak Hadir');
        $sheet->setCellValue('E' . $startRow, 'Terlambat');
        $sheet->setCellValue('F' . $startRow, 'Izin');
        $sheet->setCellValue('G' . $startRow, 'Persentase');

        // Style class summary header
        $classSummaryHeaderRange = 'A' . $startRow . ':G' . $startRow;
        $sheet->getStyle($classSummaryHeaderRange)->getFont()->setBold(true);
        $sheet->getStyle($classSummaryHeaderRange)->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle($classSummaryHeaderRange)->getFill()->getStartColor()->setRGB('DCE6F0');
        $sheet->getStyle($classSummaryHeaderRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Fill class summary data
        $startRow++;
        if (!empty($this->classSummary)) {
            foreach ($this->classSummary as $summary) {
                $sheet->setCellValue('A' . $startRow, $summary['class'] ?? '-');
                $sheet->setCellValue('B' . $startRow, $summary['total_students'] ?? 0);
                $sheet->setCellValue('C' . $startRow, $summary['present'] ?? 0);
                $sheet->setCellValue('D' . $startRow, $summary['absent'] ?? 0);
                $sheet->setCellValue('E' . $startRow, $summary['late'] ?? 0);
                $sheet->setCellValue('F' . $startRow, $summary['excused'] ?? 0);
                $sheet->setCellValue('G' . $startRow, ($summary['percentage'] ?? 0) . '%');

                // Color percentage based on value
                $percentage = $summary['percentage'] ?? 0;
                $cellStyle = $sheet->getStyle('G' . $startRow);
                $cellStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                if ($percentage >= 90) {
                    $cellStyle->getFont()->getColor()->setRGB('008000');
                    $cellStyle->getFill()->setFillType(Fill::FILL_SOLID);
                    $cellStyle->getFill()->getStartColor()->setRGB('E6FFE6');
                } elseif ($percentage >= 75) {
                    $cellStyle->getFont()->getColor()->setRGB('FFA500');
                    $cellStyle->getFill()->setFillType(Fill::FILL_SOLID);
                    $cellStyle->getFill()->getStartColor()->setRGB('FFF5E6');
                } else {
                    $cellStyle->getFont()->getColor()->setRGB('FF0000');
                    $cellStyle->getFill()->setFillType(Fill::FILL_SOLID);
                    $cellStyle->getFill()->getStartColor()->setRGB('FFE6E6');
                }
                $startRow++;
            }
        }

        // Footer
        $startRow += 2;
        $sheet->setCellValue('A' . $startRow, 'Dicetak pada: ' . Carbon::now()->translatedFormat('d F Y H:i:s'));
        $sheet->mergeCells('A' . $startRow . ':N' . $startRow);
        $sheet->getStyle('A' . $startRow)->getFont()->setItalic(true);
        $sheet->getStyle('A' . $startRow)->getFont()->setSize(10);
        $sheet->getStyle('A' . $startRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    private function getRoleText($role)
    {
        $roleMap = [
            'admin' => 'Administrator',
            'guru' => 'Guru',
            'siswa' => 'Siswa'
        ];
        return $roleMap[$role] ?? ucfirst($role);
    }

    private function getAttendanceStatusText($status)
    {
        $statusMap = [
            'present' => 'Hadir',
            'absent' => 'Tidak Hadir',
            'late' => 'Terlambat',
            'excused' => 'Izin'
        ];
        return $statusMap[$status] ?? ucfirst($status);
    }

    private function getTimeStatus($attendance)
    {
        if (!$attendance->time) {
            return '-';
        }

        try {
            $time = Carbon::parse($attendance->time);
            $scheduleTime = Carbon::parse('07:30:00');

            if ($time->gt($scheduleTime->copy()->addMinutes(15))) {
                return 'Terlambat';
            } elseif ($time->gt($scheduleTime)) {
                return 'Tepat Waktu';
            } else {
                return 'Terlalu Cepat';
            }
        } catch (\Exception $e) {
            return '-';
        }
    }

    private function getIndonesianDay($englishDay)
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        return $days[$englishDay] ?? $englishDay;
    }
}