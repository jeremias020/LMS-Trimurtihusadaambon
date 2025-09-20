<?php

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PracticalStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'guru';
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date|after_or_equal:today',
            'lokasi' => 'nullable|string|max:255',
            'durasi' => 'required|integer|min:1|max:480',
            'alat' => 'nullable|string|max:500',
            'bahan' => 'nullable|string|max:500',
            'instruksi' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'prosedur_keselamatan' => 'nullable|string|max:1000',
            'standar_kesehatan' => 'nullable|string|max:1000',
            'is_published' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul praktikum wajib diisi.',
            'judul.string' => 'Judul harus berupa teks.',
            'judul.max' => 'Judul tidak boleh lebih dari 255 karakter.',

            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',

            'tanggal.required' => 'Tanggal praktikum wajib diisi.',
            'tanggal.date' => 'Tanggal harus berupa tanggal yang valid.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh sebelum hari ini.',

            'lokasi.string' => 'Lokasi harus berupa teks.',
            'lokasi.max' => 'Lokasi tidak boleh lebih dari 255 karakter.',

            'durasi.required' => 'Durasi wajib diisi.',
            'durasi.integer' => 'Durasi harus berupa angka.',
            'durasi.min' => 'Durasi minimal adalah 1 menit.',
            'durasi.max' => 'Durasi maksimal adalah 480 menit.',

            'alat.string' => 'Alat harus berupa teks.',
            'alat.max' => 'Alat tidak boleh lebih dari 500 karakter.',

            'bahan.string' => 'Bahan harus berupa teks.',
            'bahan.max' => 'Bahan tidak boleh lebih dari 500 karakter.',

            'instruksi.required' => 'Instruksi wajib diisi.',
            'instruksi.string' => 'Instruksi harus berupa teks.',

            'kelas_id.required' => 'Kelas wajib dipilih.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',

            'prosedur_keselamatan.string' => 'Prosedur keselamatan harus berupa teks.',
            'prosedur_keselamatan.max' => 'Prosedur keselamatan tidak boleh lebih dari 1000 karakter.',

            'standar_kesehatan.string' => 'Standar kesehatan harus berupa teks.',
            'standar_kesehatan.max' => 'Standar kesehatan tidak boleh lebih dari 1000 karakter.',

            'is_published.boolean' => 'Status publikasi harus true atau false.',
        ];
    }

    public function attributes(): array
    {
        return [
            'judul' => 'judul praktikum',
            'deskripsi' => 'deskripsi',
            'tanggal' => 'tanggal praktikum',
            'lokasi' => 'lokasi',
            'durasi' => 'durasi',
            'alat' => 'alat',
            'bahan' => 'bahan',
            'instruksi' => 'instruksi',
            'kelas_id' => 'kelas',
            'prosedur_keselamatan' => 'prosedur keselamatan',
            'standar_kesehatan' => 'standar kesehatan',
            'is_published' => 'status publikasi',
        ];
    }
}