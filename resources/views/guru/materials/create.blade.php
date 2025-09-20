@extends('layouts.guru')

@section('title', 'Tambah Materi Pembelajaran')
@section('page-title', 'Tambah Materi Pembelajaran')
@section('page-subtitle', 'Buat materi pembelajaran baru untuk siswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('guru.materials.index') }}">Materi Pembelajaran</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Materi</li>
@endsection

@section('page-actions')
    <a href="{{ route('guru.materials.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Kembali ke Daftar Materi
    </a>
@endsection

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<style>
:root {
    --primary-blue: #1e40af;
    --primary-blue-light: #3b82f6;
    --secondary-green: #059669;
    --accent-orange: #ea580c;
    --accent-purple: #7c3aed;
    --neutral-gray: #6b7280;
    --light-gray: #f8fafc;
    --border-color: #e2e8f0;
}

body {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
}

.page-header {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-light) 100%);
    border-radius: 16px;
    padding: 2rem;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 10px 25px rgba(30, 64, 175, 0.2);
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: translate(50%, -50%);
}

.page-header h1 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 2;
}

.page-header p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
    position: relative;
    z-index: 2;
}

.form-container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border: 1px solid var(--border-color);
}

.form-header {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-color);
}

.form-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--primary-blue);
    margin: 0;
    display: flex;
    align-items: center;
}

.form-header .icon {
    width: 24px;
    height: 24px;
    margin-right: 0.75rem;
    color: var(--primary-blue-light);
}

.form-content {
    padding: 2rem;
}

.step-indicator {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2rem;
    position: relative;
}

.step-indicator::before {
    content: '';
    position: absolute;
    top: 1rem;
    left: 2rem;
    right: 2rem;
    height: 2px;
    background: var(--border-color);
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
}

.step-circle {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: white;
    border: 3px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--neutral-gray);
    transition: all 0.3s ease;
}

.step.active .step-circle {
    background: var(--primary-blue-light);
    border-color: var(--primary-blue-light);
    color: white;
    transform: scale(1.1);
}

.step.completed .step-circle {
    background: var(--secondary-green);
    border-color: var(--secondary-green);
    color: white;
}

.step-label {
    font-size: 0.75rem;
    color: var(--neutral-gray);
    margin-top: 0.5rem;
    text-align: center;
    font-weight: 500;
}

.step.active .step-label {
    color: var(--primary-blue);
    font-weight: 600;
}

.form-section {
    margin-bottom: 2rem;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.form-section:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.section-header {
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid var(--border-color);
}

.section-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    color: var(--primary-blue);
}

.section-icon {
    width: 20px;
    height: 20px;
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.section-content {
    padding: 1.5rem;
    background: white;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.form-label .required {
    color: #ef4444;
    margin-left: 0.25rem;
}

.form-input {
    width: 100%;
    padding: 0.875rem;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    background-color: #fff;
    color: #111827;
    font-size: 0.875rem;
    line-height: 1.5;
    transition: all 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary-blue-light);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    transform: translateY(-1px);
}

.form-input:hover {
    border-color: #c7d2fe;
}

.file-upload-area {
    border: 2px dashed var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    background: #fafbfc;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    min-height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
}

.file-upload-area:hover {
    border-color: var(--primary-blue-light);
    background: #f0f7ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.file-upload-area:active {
    transform: translateY(0) scale(0.98);
}

.file-upload-area.dragover {
    border-color: var(--secondary-green);
    background: #f0fdf4;
    transform: scale(1.02);
    box-shadow: 0 8px 25px rgba(5, 150, 105, 0.15);
}

.file-upload-area * {
    pointer-events: none;
}

.file-upload-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 1rem;
    color: var(--neutral-gray);
}

.file-upload-text {
    font-weight: 600;
    color: var(--primary-blue);
    margin-bottom: 0.5rem;
}

.file-upload-hint {
    font-size: 0.875rem;
    color: var(--neutral-gray);
    margin-bottom: 1rem;
}

.file-formats {
    font-size: 0.75rem;
    color: var(--neutral-gray);
    background: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    display: inline-block;
}

.checkbox-group {
    background: #f8fafc;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 1rem;
    transition: all 0.3s ease;
}

.checkbox-group:hover {
    background: #f1f5f9;
    border-color: var(--primary-blue-light);
}

.checkbox-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: var(--primary-blue-light);
}

.checkbox-label {
    margin-left: 0.75rem;
    font-weight: 500;
    color: #374151;
    cursor: pointer;
}

.form-actions {
    background: #f8fafc;
    padding: 1.5rem 2rem;
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    padding: 0.875rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    cursor: pointer;
    font-size: 0.875rem;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-light) 100%);
    color: white;
    box-shadow: 0 4px 14px rgba(59, 130, 246, 0.3);
}

.btn-primary:hover {
    color: white;
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
}

.btn-secondary {
    background: white;
    color: var(--neutral-gray);
    border-color: var(--border-color);
}

.btn-secondary:hover {
    background: #f8fafc;
    color: #374151;
    border-color: var(--neutral-gray);
}

.btn-icon {
    width: 16px;
    height: 16px;
    margin-right: 0.5rem;
}

.error-message {
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
}

.error-icon {
    width: 16px;
    height: 16px;
    margin-right: 0.5rem;
}

.help-text {
    font-size: 0.75rem;
    color: var(--neutral-gray);
    margin-top: 0.5rem;
    display: flex;
    align-items: flex-start;
}

.help-icon {
    width: 14px;
    height: 14px;
    margin-right: 0.5rem;
    margin-top: 1px;
    flex-shrink: 0;
}

.grid {
    display: grid;
}

.grid-cols-1 {
    grid-template-columns: repeat(1, minmax(0, 1fr));
}

.gap-6 {
    gap: 1.5rem;
}

@media (min-width: 768px) {
    .md\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    
    .form-actions {
        justify-content: flex-end;
    }
}

@media (max-width: 767px) {
    .page-header {
        padding: 1.5rem;
    }
    
    .page-header h1 {
        font-size: 1.5rem;
    }
    
    .form-content {
        padding: 1rem;
    }
    
    .section-content {
        padding: 1rem;
    }
    
    .step-indicator {
        display: none;
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.animate-delay-1 {
    animation-delay: 0.1s;
}

.animate-delay-2 {
    animation-delay: 0.2s;
}

.animate-delay-3 {
    animation-delay: 0.3s;
}

/* Loading spinner */
.spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Additional utility classes */
.flex {
    display: flex;
}

.items-center {
    align-items: center;
}

.justify-center {
    justify-content: center;
}

.justify-between {
    justify-content: space-between;
}

.hidden {
    display: none;
}

.text-center {
    text-align: center;
}

.mx-auto {
    margin-left: auto;
    margin-right: auto;
}

.mb-1 {
    margin-bottom: 0.25rem;
}

.mb-2 {
    margin-bottom: 0.5rem;
}

.mb-4 {
    margin-bottom: 1rem;
}

.mt-1 {
    margin-top: 0.25rem;
}

.mt-3 {
    margin-top: 0.75rem;
}

.mr-2 {
    margin-right: 0.5rem;
}

.mr-3 {
    margin-right: 0.75rem;
}

.p-3 {
    padding: 0.75rem;
}

.p-6 {
    padding: 1.5rem;
}

.px-3 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
}

.px-6 {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
}

.py-2 {
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
}

.py-3 {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
}

.w-5 {
    width: 1.25rem;
}

.w-8 {
    width: 2rem;
}

.w-12 {
    width: 3rem;
}

.w-16 {
    width: 4rem;
}

.h-5 {
    height: 1.25rem;
}

.h-8 {
    height: 2rem;
}

.h-12 {
    height: 3rem;
}

.h-16 {
    height: 4rem;
}

.text-xs {
    font-size: 0.75rem;
    line-height: 1rem;
}

.text-sm {
    font-size: 0.875rem;
    line-height: 1.25rem;
}

.text-lg {
    font-size: 1.125rem;
    line-height: 1.75rem;
}

.font-bold {
    font-weight: 700;
}

.font-medium {
    font-weight: 500;
}

.text-blue-500 {
    color: #3b82f6;
}

.text-blue-600 {
    color: #2563eb;
}

.text-blue-700 {
    color: #1d4ed8;
}

.text-blue-800 {
    color: #1e40af;
}

.text-green-500 {
    color: #10b981;
}

.text-green-600 {
    color: #059669;
}

.text-green-800 {
    color: #065f46;
}

.text-red-500 {
    color: #ef4444;
}

.text-red-800 {
    color: #991b1b;
}

.text-gray-500 {
    color: #6b7280;
}

.bg-white {
    background-color: #ffffff;
}

.bg-blue-50 {
    background-color: #eff6ff;
}

.bg-blue-600 {
    background-color: #2563eb;
}

.bg-green-50 {
    background-color: #f0fdf4;
}

.bg-red-50 {
    background-color: #fef2f2;
}

.bg-opacity-20 {
    background-color: rgba(255, 255, 255, 0.2);
}

.border {
    border-width: 1px;
}

.border-2 {
    border-width: 2px;
}

.border-blue-200 {
    border-color: #bfdbfe;
}

.border-green-200 {
    border-color: #bbf7d0;
}

.border-red-200 {
    border-color: #fecaca;
}

.rounded-lg {
    border-radius: 0.5rem;
}

.cursor-pointer {
    cursor: pointer;
}

.transition-colors {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

.hover\:bg-blue-700:hover {
    background-color: #1d4ed8;
}

.hover\:bg-yellow-600:hover {
    background-color: #d97706;
}

.max-w-md {
    max-width: 28rem;
}

.inline-block {
    display: inline-block;
}

.bg-yellow-500 {
    background-color: #f59e0b;
}
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="page-header animate-fade-in-up">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="flex items-center">
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Tambah Materi Pembelajaran Baru
            </h1>
            <p>Buat materi pembelajaran berkualitas untuk siswa SMK Kesehatan Trimurti Husada</p>
        </div>
        <div class="hidden md:block">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Main Form Container -->
<div class="form-container animate-fade-in-up animate-delay-1">
    <!-- Form Header -->
    <div class="form-header">
        <h2>
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Formulir Tambah Materi
        </h2>
        <p class="mt-2 text-sm opacity-75">Lengkapi semua informasi yang diperlukan untuk membuat materi pembelajaran yang efektif</p>
    </div>

    <!-- Form Content -->
    <form action="{{ route('guru.materials.store') }}" method="POST" enctype="multipart/form-data" id="materialForm">
        @csrf
        
        <div class="form-content">
            <!-- Progress Steps -->
            <div class="step-indicator">
                <div class="step active" data-step="1">
                    <div class="step-circle">1</div>
                    <div class="step-label">Informasi Dasar</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-circle">2</div>
                    <div class="step-label">Deskripsi</div>
                </div>
                <div class="step" data-step="3">
                    <div class="step-circle">3</div>
                    <div class="step-label">Upload File</div>
                </div>
            </div>

            <!-- Section 1: Basic Information -->
            <div class="form-section animate-fade-in-up animate-delay-2">
                <div class="section-header">
                    <h3 class="section-title">
                        <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Informasi Dasar Materi
                    </h3>
                </div>
                <div class="section-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="judul" class="form-label">
                                Judul Materi <span class="required">*</span>
                            </label>
                            <input type="text" name="judul" id="judul" class="form-input"
                                   value="{{ old('judul') }}" 
                                   placeholder="Contoh: Anatomi Sistem Pencernaan Manusia" 
                                   required>
                            @error('judul')
                                <div class="error-message">
                                    <svg class="error-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="help-text">
                                <svg class="help-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Berikan judul yang jelas dan deskriptif untuk materi pembelajaran
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="subject_id" class="form-label">
                                Mata Pelajaran <span class="required">*</span>
                            </label>
                            <select name="subject_id" id="subject_id" class="form-input" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="error-message">
                                    <svg class="error-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category" class="form-label">
                                Kategori Materi <span class="required">*</span>
                            </label>
                            <select name="category" id="category" class="form-input" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $key => $value)
                                <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="error-message">
                                    <svg class="error-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Pengaturan Publikasi</label>
                            <div class="checkbox-group">
                                <input type="checkbox" name="is_published" id="is_published" value="1" 
                                       {{ old('is_published') ? 'checked' : '' }}>
                                <span class="checkbox-label">
                                    <strong>Publikasikan materi</strong><br>
                                    <small class="text-gray-500">Siswa dapat langsung melihat dan mengakses materi ini</small>
                                </span>
                            </div>
                            @error('is_published')
                                <div class="error-message">
                                    <svg class="error-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Description -->
            <div class="form-section animate-fade-in-up animate-delay-3">
                <div class="section-header">
                    <h3 class="section-title">
                        <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Deskripsi Materi
                    </h3>
                </div>
                <div class="section-content">
                    <div class="form-group">
                        <label for="description" class="form-label">Deskripsi Pembelajaran</label>
                        <textarea name="description" id="description" class="form-input" rows="6"
                                  placeholder="Jelaskan tentang:
• Tujuan pembelajaran
• Materi yang akan dipelajari
• Metode pembelajaran yang digunakan
• Kompetensi yang akan dicapai
• Tips atau catatan khusus untuk siswa">{{ old('description') }}</textarea>
                        <div class="help-text">
                            <svg class="help-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            Opsional - berikan deskripsi lengkap untuk membantu siswa memahami materi dengan lebih baik
                        </div>
                        @error('description')
                            <div class="error-message">
                                <svg class="error-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: File Upload -->
            <div class="form-section animate-fade-in-up animate-delay-3">
                <div class="section-header">
                    <h3 class="section-title">
                        <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Upload File Materi
                    </h3>
                </div>
                <div class="section-content">
                    <div class="form-group">
                        <label for="file" class="form-label">
                            File Materi <span class="required">*</span>
                        </label>
                        
                        <!-- SUPER SIMPLE File Upload -->
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50">
                            <!-- File Input (Visible) -->
                            <input type="file" id="file" name="file" 
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 mb-4"
                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip,.rar,.mp4,.avi,.mov,.jpg,.jpeg,.png"
                                   required onchange="handleFileSelect(this)">
                            
                            <div class="mt-2">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">File yang didukung: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, ZIP, RAR, MP4, AVI, MOV, JPG, PNG</p>
                                <p class="text-xs text-red-500">Maksimal 50MB</p>
                            </div>
                        </div>
                        
                        <!-- File Preview (Always Visible Container) -->
                        <div id="filePreviewContainer" class="mt-4">
                            <!-- This will be populated by JavaScript -->
                        </div>
                            
                            <div class="text-xs text-gray-500 bg-white inline-block px-3 py-2 rounded-lg">
                                <div class="font-medium mb-1">Format yang didukung:</div>
                                <div>PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, ZIP, RAR, MP4, AVI, MOV, JPG, PNG</div>
                                <div class="mt-1 text-xs text-red-500 font-medium">Ukuran maksimal: 50MB</div>
                            </div>
                            
                            </div>
                        
                        @error('file')
                            <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center text-red-800">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('guru.materials.index') }}" class="btn btn-secondary">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Batal
            </a>
            
            <button type="submit" class="btn btn-primary" id="submitBtn">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Materi
            </button>
        </div>
    </form>
</div>

@push('styles')
<style>
/* Ensure file preview is visible when shown */
#fileInfo {
    transition: all 0.3s ease;
}

#fileInfo:not(.hidden) {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.file-preview-area {
    min-height: 60px;
    border: 2px dashed #e2e8f0;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.file-preview-area.has-file {
    border-color: #10b981;
    background-color: #ecfdf5;
}

.upload-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

/* Animation for file preview */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.file-preview-show {
    animation: slideDown 0.3s ease-out;
}
</style>
@endpush

@push('scripts')
<script>
// SIMPLE file handler that WILL work
function handleFileSelect(input) {
    console.log('File input changed!');
    
    const container = document.getElementById('filePreviewContainer');
    if (!container) {
        console.error('Preview container not found!');
        return;
    }
    
    // Clear previous content
    container.innerHTML = '';
    
    if (!input.files || input.files.length === 0) {
        console.log('No file selected');
        return;
    }
    
    const file = input.files[0];
    console.log('File selected:', file.name, (file.size / 1024 / 1024).toFixed(2) + ' MB');
    
    // Validate file size
    const maxSize = 50 * 1024 * 1024; // 50MB
    if (file.size > maxSize) {
        alert('File terlalu besar! Maksimal 50MB.');
        input.value = '';
        return;
    }
    
    // Create preview element DIRECTLY
    const previewHTML = `
        <div class="bg-green-50 border-2 border-green-200 rounded-lg p-4 max-w-md mx-auto">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-green-800">${file.name}</div>
                        <div class="text-sm text-green-600">${(file.size / 1024 / 1024).toFixed(2)} MB</div>
                        <div class="text-xs text-green-500 mt-1">✅ Siap diupload</div>
                    </div>
                </div>
                <button type="button" onclick="clearFileSelection()" class="text-red-500 hover:text-red-700 p-1 rounded">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    `;
    
    // Insert directly into DOM
    container.innerHTML = previewHTML;
    
    console.log('Preview created successfully!');
    
    // Show notification
    showNotification('✅ File berhasil dipilih: ' + file.name, 'success');
    
    // Update progress
    updateStepProgress(3);
}

// Clear file selection
function clearFileSelection() {
    const input = document.getElementById('file');
    const container = document.getElementById('filePreviewContainer');
    
    if (input) input.value = '';
    if (container) container.innerHTML = '';
    
    showNotification('File dihapus', 'info');
}


// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Upload system ready!');
    showNotification('System siap - silakan pilih file', 'info');
});


// Simple notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    
    notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
    notification.style.animation = 'slideIn 0.3s ease-out';
    notification.innerHTML = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Update step progress
function updateStepProgress(step) {
    const steps = document.querySelectorAll('.step');
    
    steps.forEach((s, index) => {
        const stepNumber = index + 1;
        s.classList.remove('active', 'completed');
        
        if (stepNumber < step) {
            s.classList.add('completed');
        } else if (stepNumber === step) {
            s.classList.add('active');
        }
    });
}

// Form validation and submission
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    
    const form = document.getElementById('materialForm');
    const submitBtn = document.getElementById('submitBtn');
    const fileInput = document.getElementById('file');
    
    console.log('Elements on load:', { form: !!form, submitBtn: !!submitBtn, fileInput: !!fileInput });
    
    // Alternative file input event listener
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            console.log('File input change event triggered');
            showFileName(this);
        });
        console.log('File input event listener added');
    } else {
        console.error('File input not found!');
    }
    
    // Track form field changes for step progress
    const judulInput = document.getElementById('judul');
    if (judulInput) {
        judulInput.addEventListener('input', function() {
            if (this.value.trim()) {
                updateStepProgress(2);
            }
        });
    }
    
    // Form submission
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // Basic validation
            const judul = document.getElementById('judul').value.trim();
            const subjectId = document.getElementById('subject_id').value;
            const category = document.getElementById('category').value;
            const file = document.getElementById('file').files[0];
            
            if (!judul) {
                e.preventDefault();
                showNotification('Judul materi wajib diisi!', 'error');
                document.getElementById('judul').focus();
                return;
            }
            
            if (!subjectId) {
                e.preventDefault();
                showNotification('Mata pelajaran wajib dipilih!', 'error');
                document.getElementById('subject_id').focus();
                return;
            }
            
            if (!category) {
                e.preventDefault();
                showNotification('Kategori materi wajib dipilih!', 'error');
                document.getElementById('category').focus();
                return;
            }
            
            if (!file) {
                e.preventDefault();
                showNotification('File materi wajib diupload!', 'error');
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="btn-icon animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan Materi...
            `;
            
            showNotification('Menyimpan materi pembelajaran...', 'info');
        });
    }
});

// Add simple CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .hidden {
        display: none;
    }
`;
document.head.appendChild(style);
</script>
@endpush
@endsection
