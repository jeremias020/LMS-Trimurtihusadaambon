<?php

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MaterialUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'guru';
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar,mp4,avi,mov,jpg,jpeg,png|max:20480',
            'kelas_id' => 'required|exists:kelas,id',
            'is_published' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul materi wajib diisi.',
            'judul.string' => 'Judul harus berupa teks.',
            'judul.max' => 'Judul tidak boleh lebih dari 255 karakter.',

            'deskripsi.string' => 'Deskripsi harus berupa teks.',

            'file.file' => 'File harus berupa file yang valid.',
            'file.mimes' => 'File harus dalam format: pdf, doc, docx, ppt, pptx, xls, xlsx, txt, zip, rar, mp4, avi, mov, jpg, jpeg, png.',
            'file.max' => 'Ukuran file tidak boleh lebih dari 20MB.',

            'kelas_id.required' => 'Kelas wajib dipilih.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',

            'is_published.boolean' => 'Status publikasi harus true atau false.',
        ];
    }

    public function attributes(): array
    {
        return [
            'judul' => 'judul materi',
            'deskripsi' => 'deskripsi',
            'file' => 'file materi',
            'kelas_id' => 'kelas',
            'is_published' => 'status publikasi',
        ];
    }
}