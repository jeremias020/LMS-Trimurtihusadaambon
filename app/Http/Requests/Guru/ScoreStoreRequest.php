<?php

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ScoreStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'guru';
    }

    public function rules(): array
    {
        return [
            'praktikum_id' => 'required|exists:praktikum,id',
            'siswa_id' => 'required|exists:siswas,id',
            'kriteria_id' => 'required|exists:kriteria,id',
            'nilai' => 'required|numeric|min:0|max:100',
            'umpan_balik' => 'nullable|string|max:1000',
            'is_final' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'praktikum_id.required' => 'Praktikum wajib dipilih.',
            'praktikum_id.exists' => 'Praktikum yang dipilih tidak valid.',

            'siswa_id.required' => 'Siswa wajib dipilih.',
            'siswa_id.exists' => 'Siswa yang dipilih tidak valid.',

            'kriteria_id.required' => 'Kriteria penilaian wajib dipilih.',
            'kriteria_id.exists' => 'Kriteria yang dipilih tidak valid.',

            'nilai.required' => 'Nilai wajib diisi.',
            'nilai.numeric' => 'Nilai harus berupa angka.',
            'nilai.min' => 'Nilai minimal adalah 0.',
            'nilai.max' => 'Nilai maksimal adalah 100.',

            'umpan_balik.string' => 'Umpan balik harus berupa teks.',
            'umpan_balik.max' => 'Umpan balik tidak boleh lebih dari 1000 karakter.',

            'is_final.boolean' => 'Status final harus true atau false.',
        ];
    }

    public function attributes(): array
    {
        return [
            'praktikum_id' => 'praktikum',
            'siswa_id' => 'siswa',
            'kriteria_id' => 'kriteria',
            'nilai' => 'nilai',
            'umpan_balik' => 'umpan balik',
            'is_final' => 'status final',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Pastikan nilai adalah angka dan dalam range yang benar
        if ($this->has('nilai')) {
            $nilai = (float) $this->nilai;
            $nilai = max(0, min(100, $nilai)); // Clamp antara 0-100
            $this->merge(['nilai' => $nilai]);
        }

        // Set default value untuk is_final jika tidak diisi
        $this->merge([
            'is_final' => $this->boolean('is_final', false),
        ]);
    }
}