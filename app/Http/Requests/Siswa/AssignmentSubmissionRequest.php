<?php

namespace App\Http\Requests\Siswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AssignmentSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'siswa'; // ✅ Perbaikan: 'student' → 'siswa'
    }

    public function rules(): array
    {
        return [
            'submission_text' => 'nullable|string|max:2000',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt,zip,rar,jpg,jpeg,png,mp4,avi,mov|max:10240',
            'link' => 'nullable|url|max:255',
            'notes' => 'nullable|string|max:500',
            'clinical_notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'submission_text.string' => 'Submission text must be text.',
            'submission_text.max' => 'Submission text cannot exceed 2000 characters.',

            'file.file' => 'File must be a valid file.',
            'file.mimes' => 'File must be in format: pdf, doc, docx, txt, zip, rar, jpg, jpeg, png, mp4, avi, mov.',
            'file.max' => 'File size cannot exceed 10MB.',

            'link.url' => 'Link must be a valid URL.',
            'link.max' => 'Link cannot exceed 255 characters.',

            'notes.string' => 'Notes must be text.',
            'notes.max' => 'Notes cannot exceed 500 characters.',

            'clinical_notes.string' => 'Clinical notes must be text.',
            'clinical_notes.max' => 'Clinical notes cannot exceed 1000 characters.',
        ];
    }

    public function attributes(): array
    {
        return [
            'submission_text' => 'submission text',
            'file' => 'file',
            'link' => 'link',
            'notes' => 'notes',
            'clinical_notes' => 'clinical notes',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->hasFile('file')) {
            $this->merge(['link' => null]);
        }

        if ($this->filled('link')) {
            $this->request->remove('file');
        }
    }
}
