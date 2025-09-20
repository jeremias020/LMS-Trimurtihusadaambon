<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NilaiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $scores;

    public function __construct($scores)
    {
        $this->scores = $scores;
    }

    public function collection()
    {
        return collect($this->scores);
    }

    public function headings(): array
    {
        return [
            'Mata Pelajaran',
            'Semester',
            'Nilai Tugas',
            'Nilai Praktikum',
            'Nilai UTS',
            'Nilai UAS',
            'Nilai Akhir',
            'Predikat'
        ];
    }

    public function map($score): array
    {
        $finalGrade = $score['final_grade'] ?? null;
        $predikat = '-';
        
        if ($finalGrade !== null) {
            if ($finalGrade >= 90) {
                $predikat = 'A';
            } elseif ($finalGrade >= 80) {
                $predikat = 'B';
            } elseif ($finalGrade >= 70) {
                $predikat = 'C';
            } elseif ($finalGrade >= 60) {
                $predikat = 'D';
            } else {
                $predikat = 'E';
            }
        }

        return [
            key($this->scores), // Ambil nama subject
            $score['semester'],
            $score['assignment_score'] ?? '-',
            $score['practical_score'] ?? '-',
            $score['midterm_score'] ?? '-',
            $score['final_score'] ?? '-',
            $finalGrade ?? '-',
            $predikat
        ];
    }
}