@extends('layouts.siswa')

@section('title', $assignment->title . ' - LMS Trimurti Husada')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('siswa.assignments.index') }}">Tugas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $assignment->title }}</li>
                </ol>
            </nav>
            <h2 class="font-weight-bold mb-4">{{ $assignment->title }}</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Tugas</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Deskripsi Tugas</h5>
                        <div class="bg-light p-3 rounded">
                            {!! nl2br(e($assignment->description)) !!}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informasi Tugas</h5>
                            <table class="table table-sm">
                                @php
                                    $deadline = $assignment->due_date ?? $assignment->deadline ?? null;
                                @endphp
                                <tr>
                                    <th width="150">Mata Pelajaran</th>
                                    <td>{{ $assignment->subject ?? 'Tidak tersedia' }}</td>
                                </tr>
                                <tr>
                                    <th>Kelas</th>
                                    <td>{{ $assignment->class_level ?? 'Tidak tersedia' }}</td>
                                </tr>
                                <tr>
                                    <th>Batas Waktu</th>
                                    <td>
                                        <span title="{{ $deadline?->translatedFormat('l, d F Y H:i') ?? '-' }}">
                                            {{ $deadline?->format('d M Y H:i') ?? '-' }}
                                        </span>
                                        @if($deadline?->isPast())
                                            <span class="badge bg-danger ms-2">Terlambat</span>
                                        @elseif($deadline && $deadline->diffInHours(now()) <= 24)
                                            <span class="badge bg-warning text-dark ms-2">Segera</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dibuat Oleh</th>
                                    <td>{{ optional($assignment->teacher)->name ?? 'Tidak tersedia' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Status Pengumpulan</h5>
                            @if($submission)
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i>
                                    <strong>Tugas telah dikumpulkan</strong>
                                    <p class="mb-0">Pada: {{ $submission->submitted_at?->format('d M Y H:i') ?? '-' }}</p>
                                    @if($submission->score !== null)
                                        <p class="mb-0">Nilai: <strong class="text-info">{{ $submission->score }}/100</strong></p>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Belum dikumpulkan</strong>
                                    <p class="mb-0">Silakan kumpulkan sebelum batas waktu</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($assignment->attachments)
                        <div class="mb-4">
                            <h5>File Lampiran</h5>
                            <div class="list-group">
                                @foreach(json_decode($assignment->attachments) as $attachment)
                                    <a href="{{ Storage::url('assignments/' . $attachment) }}"
                                       class="list-group-item list-group-item-action"
                                       download="{{ $attachment }}"
                                       title="Unduh {{ $attachment }}">
                                        <i class="fas fa-file-download me-2"></i> {{ $attachment }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Form Pengumpulan Tugas -->
            <div class="card shadow mb-4" id="submit">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pengumpulan Tugas</h6>
                </div>
                <div class="card-body">
                    @if($submission)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Anda sudah mengumpulkan tugas ini.
                            @if($submission->feedback)
                                <hr>
                                <h6>Feedback Guru:</h6>
                                <div class="bg-light p-3 rounded">
                                    {!! nl2br(e($submission->feedback)) !!}
                                </div>
                            @endif
                        </div>
                        @if($submission->file_path)
                            <a href="{{ Storage::url('submissions/' . $submission->file_path) }}"
                               class="btn btn-primary btn-block mb-3"
                               download="{{ basename($submission->file_path) }}"
                               title="Unduh file yang Anda kumpulkan">
                                <i class="fas fa-download"></i> Unduh File yang Dikumpulkan
                            </a>
                        @endif
                    @elseif($deadline?->isPast())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <strong>Batas waktu pengumpulan telah berlalu.</strong>
                            <p class="mb-0">Silakan hubungi guru Anda jika ada pertanyaan.</p>
                        </div>
                    @else
                        <form action="{{ route('siswa.assignments.submit', $assignment->id) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              id="submissionForm">
                            @csrf
                            <div class="form-group">
                                <label for="submission_file" class="form-label">Unggah File Tugas *</label>
                                <input type="file"
                                       class="form-control"
                                       id="submission_file"
                                       name="file"
                                       required
                                       accept=".pdf,.doc,.docx,.txt,.zip,.rar,.jpg,.jpeg,.png"
                                       onchange="previewFile(this)">
                                <div id="filePreview" class="mt-2 text-sm text-gray-600"></div>
                                <small class="form-text text-muted">
                                    Format yang diterima: PDF, DOC, DOCX, TXT, ZIP, RAR, JPG, JPEG, PNG. Maksimal: 5MB
                                </small>
                                @error('file')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="comments" class="form-label">Komentar (Opsional)</label>
                                <textarea class="form-control"
                                          id="comments"
                                          name="submission_text"
                                          rows="3"
                                          placeholder="Tambahkan komentar atau catatan mengenai tugas ini...">{{ old('submission_text') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-block" id="submitBtn">
                                <i class="fas fa-paper-plane"></i> Kumpulkan Tugas
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Informasi Penting -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Penting</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Perhatian:</strong>
                        <ul class="mb-0 ps-3">
                            <li>Pastikan file yang diunggah sesuai dengan format yang diminta</li>
                            <li>Tugas yang terlambat akan dikenakan pengurangan nilai</li>
                            <li>Periksa kembali file sebelum mengumpulkan</li>
                            <li>Simpan bukti pengumpulan untuk jaga-jaga</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
function previewFile(input) {
    const file = input.files[0];
    const preview = document.getElementById('filePreview');

    if (!file) {
        preview.innerHTML = '';
        return;
    }

    const fileSize = (file.size / 1024 / 1024).toFixed(2);
    const fileExtension = file.name.split('.').pop().toLowerCase();

    // Check file size
    if (file.size > 5 * 1024 * 1024) {
        preview.innerHTML = `<span class="text-danger">File terlalu besar! Maksimal 5MB.</span>`;
        input.value = '';
        return;
    }

    // Check file type
    const allowedTypes = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar', 'jpg', 'jpeg', 'png'];
    if (!allowedTypes.includes(fileExtension)) {
        preview.innerHTML = `<span class="text-danger">Format file tidak diizinkan!</span>`;
        input.value = '';
        return;
    }

    preview.innerHTML = `
        <div class="border p-2 rounded bg-light">
            <strong>File:</strong> ${file.name}<br>
            <strong>Ukuran:</strong> ${fileSize} MB<br>
            <strong>Tipe:</strong> ${fileExtension.toUpperCase()}
        </div>
    `;
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('submissionForm');
    const submitBtn = document.getElementById('submitBtn');

    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Mengunggah...
            `;
        });
    }
});
</script>
@endpush
