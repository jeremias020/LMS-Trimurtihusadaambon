<?php

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MaterialStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'guru' && Auth::user()->status === 'aktif';
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,txt,zip,rar,mp4,avi,mov,jpg,jpeg,png|max:20480',
            'deskripsi' => 'nullable|string|max:1000',
            'is_published' => 'sometimes|boolean',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'kelas' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul materi wajib diisi.',
            'judul.max' => 'Judul materi maksimal 255 karakter.',
            
            'file.required' => 'File materi wajib diupload.',
            'file.file' => 'File harus berupa file yang valid.',
            'file.mimes' => 'File harus berupa PDF, DOC, DOCX, PPT, PPTX, TXT, ZIP, RAR, MP4, AVI, MOV, JPG, JPEG, atau PNG.',
            'file.max' => 'File tidak boleh lebih dari 20MB.',
            
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',
            
            'mata_pelajaran_id.required' => 'Mata pelajaran wajib dipilih.',
            'mata_pelajaran_id.exists' => 'Mata pelajaran yang dipilih tidak valid.',
            
            'kelas.required' => 'Kelas wajib diisi.',
            'kelas.max' => 'Nama kelas maksimal 50 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'judul' => 'judul materi',
            'file' => 'file materi',
            'deskripsi' => 'deskripsi materi',
            'is_published' => 'status publikasi',
            'mata_pelajaran_id' => 'mata pelajaran',
            'kelas' => 'kelas',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published', false),
        ]);
    }
}