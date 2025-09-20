<?php

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AttendanceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'guru';
    }

    public function rules(): array
    {
        return [
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => [
                'required',
                'date',
                Rule::unique('attendances')->where(function ($query) {
                    return $query->where('siswa_id', $this->siswa_id);
                })->ignore($this->route('attendance'))
            ],
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string|max:500',
            'waktu_masuk' => 'nullable|date_format:H:i',
            'waktu_keluar' => 'nullable|date_format:H:i|after:waktu_masuk',
        ];
    }

    public function messages(): array
    {
        return [
            'siswa_id.required' => 'Siswa wajib dipilih.',
            'siswa_id.exists' => 'Siswa yang dipilih tidak valid.',

            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal harus berupa tanggal yang valid.',
            'tanggal.unique' => 'Absensi untuk siswa pada tanggal ini sudah ada.',

            'status.required' => 'Status absensi wajib diisi.',
            'status.in' => 'Status absensi tidak valid.',

            'keterangan.string' => 'Keterangan harus berupa teks.',
            'keterangan.max' => 'Keterangan tidak boleh lebih dari 500 karakter.',

            'waktu_masuk.date_format' => 'Format waktu masuk tidak valid.',

            'waktu_keluar.date_format' => 'Format waktu keluar tidak valid.',
            'waktu_keluar.after' => 'Waktu keluar harus setelah waktu masuk.',
        ];
    }

    public function attributes(): array
    {
        return [
            'siswa_id' => 'siswa',
            'tanggal' => 'tanggal',
            'status' => 'status absensi',
            'keterangan' => 'keterangan',
            'waktu_masuk' => 'waktu masuk',
            'waktu_keluar' => 'waktu keluar',
        ];
    }
}