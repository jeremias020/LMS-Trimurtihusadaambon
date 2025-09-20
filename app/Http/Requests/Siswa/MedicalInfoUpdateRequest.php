<?php

namespace App\Http\Requests\Siswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MedicalInfoUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'siswa'; // ✅ Perbaikan: 'student' → 'siswa'
    }

    public function rules(): array
    {
        return [
            'blood_type' => 'nullable|in:A,B,AB,O',
            'allergies' => 'nullable|string|max:500',
            'medical_conditions' => 'nullable|string|max:1000',
            'current_medications' => 'nullable|string|max:500',
            'insurance_info' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:20',
            'health_notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'blood_type.in' => 'Blood type must be A, B, AB, or O.',
            'allergies.max' => 'Allergies cannot exceed 500 characters.',
            'medical_conditions.max' => 'Medical conditions cannot exceed 1000 characters.',
            'current_medications.max' => 'Current medications cannot exceed 500 characters.',
            'insurance_info.max' => 'Insurance information cannot exceed 255 characters.',
            'emergency_contact.max' => 'Emergency contact cannot exceed 20 characters.',
            'health_notes.max' => 'Health notes cannot exceed 1000 characters.',
        ];
    }

    public function attributes(): array
    {
        return [
            'blood_type' => 'blood type',
            'allergies' => 'allergies',
            'medical_conditions' => 'medical conditions',
            'current_medications' => 'current medications',
            'insurance_info' => 'insurance information',
            'emergency_contact' => 'emergency contact',
            'health_notes' => 'health notes',
        ];
    }
}
