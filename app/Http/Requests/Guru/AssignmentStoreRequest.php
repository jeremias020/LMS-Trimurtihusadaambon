<?php

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AssignmentStoreRequest extends FormRequest
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
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar|max:20480',
            'deadline' => 'required|date|after:now',
            'nilai_maksimal' => 'required|integer|min:1|max:100',
            'kelas_id' => 'required|exists:kelas,id',
            'is_published' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul tugas wajib diisi.',
            'judul.string' => 'Judul harus berupa teks.',
            'judul.max' => 'Judul tidak boleh lebih dari 255 karakter.',

            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',

            'file.file' => 'File harus berupa file yang valid.',
            'file.mimes' => 'File harus dalam format: pdf, doc, docx, ppt, pptx, xls, xlsx, txt, zip, rar.',
            'file.max' => 'Ukuran file tidak boleh lebih dari 20MB.',

            'deadline.required' => 'Batas waktu wajib diisi.',
            'deadline.date' => 'Batas waktu harus berupa tanggal yang valid.',
            'deadline.after' => 'Batas waktu harus setelah waktu saat ini.',

            'nilai_maksimal.required' => 'Nilai maksimal wajib diisi.',
            'nilai_maksimal.integer' => 'Nilai maksimal harus berupa angka.',
            'nilai_maksimal.min' => 'Nilai minimal adalah 1.',
            'nilai_maksimal.max' => 'Nilai maksimal adalah 100.',

            'kelas_id.required' => 'Kelas wajib dipilih.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',

            'is_published.boolean' => 'Status publikasi harus true atau false.',
        ];
    }

    public function attributes(): array
    {
        return [
            'judul' => 'judul tugas',
            'deskripsi' => 'deskripsi',
            'file' => 'file tugas',
            'deadline' => 'batas waktu',
            'nilai_maksimal' => 'nilai maksimal',
            'kelas_id' => 'kelas',
            'is_published' => 'status publikasi',
        ];
    }
}