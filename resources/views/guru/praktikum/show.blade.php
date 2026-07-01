@extends('layouts.guru')

@section('title', 'Detail Praktikum')
@section('page-title', $praktikum->title)
@section('page-subtitle', 'Detail dan penilaian praktikum')

@section('page-actions')
    <a href="{{ route('guru.praktikum.edit', $praktikum->id) }}" class="btn btn-warning btn-sm">
        <i class="fas fa-edit me-1"></i>Edit
    </a>
    <form action="{{ route('guru.praktikum.toggle-publish', $praktikum->id) }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-sm {{ $praktikum->is_published ? 'btn-secondary' : 'btn-success' }}">
            <i class="fas fa-{{ $praktikum->is_published ? 'eye-slash' : 'eye' }} me-1"></i>
            {{ $praktikum->is_published ? 'Sembunyikan' : 'Publikasikan' }}
        </button>
    </form>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('guru.praktikum.index') }}">Praktikum</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')

{{-- Info Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stats-card text-center">
            <div class="h3 fw-bold text-primary mb-1">{{ $stats['total_siswa'] }}</div>
            <div class="small text-muted">Total Siswa</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stats-card text-center">
            <div class="h3 fw-bold text-success mb-1">{{ $stats['scored'] }}</div>
            <div class="small text-muted">Sudah Dinilai</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stats-card text-center">
            <div class="h3 fw-bold text-warning mb-1">{{ $stats['average_score'] }}</div>
            <div class="small text-muted">Rata-rata Nilai</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stats-card text-center">
            <div class="h3 fw-bold text-info mb-1">{{ $stats['passing'] }}</div>
            <div class="small text-muted">Lulus (≥70)</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Detail Info --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Informasi Praktikum</h6></div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted">Mata Pelajaran</dt>
                    <dd class="col-7">{{ $praktikum->subject?->name ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Kelas</dt>
                    <dd class="col-7">{{ $praktikum->kelas?->name ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Tanggal</dt>
                    <dd class="col-7">{{ $praktikum->due_date?->format('d M Y') ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Durasi</dt>
                    <dd class="col-7">{{ $praktikum->durasi ? $praktikum->durasi . ' menit' : '—' }}</dd>

                    <dt class="col-5 text-muted">Lokasi</dt>
                    <dd class="col-7">{{ $praktikum->lokasi ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Tingkat</dt>
                    <dd class="col-7">{{ $praktikum->skill_level ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Nilai Maks</dt>
                    <dd class="col-7">{{ $praktikum->max_score ?? 100 }}</dd>

                    <dt class="col-5 text-muted">Status</dt>
                    <dd class="col-7">
                        <span class="badge {{ $praktikum->is_published ? 'bg-success' : 'bg-secondary' }}">
                            {{ $praktikum->is_published ? 'Publik' : 'Draft' }}
                        </span>
                    </dd>
                </dl>

                @if($praktikum->description)
                    <hr>
                    <p class="small text-muted mb-1 fw-semibold">Deskripsi</p>
                    <p class="small mb-0">{{ $praktikum->description }}</p>
                @endif

                @if($praktikum->instructions)
                    <hr>
                    <p class="small text-muted mb-1 fw-semibold">Instruksi</p>
                    <p class="small mb-0" style="white-space:pre-line">{{ $praktikum->instructions }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Penilaian Siswa --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Penilaian Siswa</h6></div>
            <div class="card-body p-0">
                @if($siswas->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-users fa-2x mb-2 opacity-50"></i>
                        <p class="mb-0">Belum ada siswa di kelas ini.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Siswa</th>
                                    <th>Nilai</th>
                                    <th>Grade</th>
                                    <th>Feedback</th>
                                    <th style="width:120px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswas as $siswa)
                                @php
                                    $score = $praktikum->scores->firstWhere('siswa_id', $siswa->id);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-medium">{{ $siswa->name }}</div>
                                        <small class="text-muted">{{ $siswa->username ?? '' }}</small>
                                    </td>
                                    <td>
                                        @if($score)
                                            <span class="fw-bold {{ $score->score >= 70 ? 'text-success' : 'text-danger' }}">
                                                {{ $score->score }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($score)
                                            @php
                                                $g = $score->score >= 90 ? 'A' : ($score->score >= 80 ? 'B' : ($score->score >= 70 ? 'C' : ($score->score >= 60 ? 'D' : 'E')));
                                                $gc = ['A'=>'success','B'=>'primary','C'=>'warning','D'=>'danger','E'=>'dark'][$g];
                                            @endphp
                                            <span class="badge bg-{{ $gc }}">{{ $g }}</span>
                                        @else
                                            <span class="badge bg-secondary">—</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ \Illuminate\Support\Str::limit($score?->feedback ?? '—', 40) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#scoreModal"
                                                data-siswa-id="{{ $siswa->id }}"
                                                data-siswa-name="{{ $siswa->name }}"
                                                data-score="{{ $score?->score ?? '' }}"
                                                data-feedback="{{ $score?->feedback ?? '' }}">
                                            <i class="fas fa-star me-1"></i>Nilai
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Score Modal --}}
<div class="modal fade" id="scoreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('guru.praktikum.score', $praktikum->id) }}" method="POST">
                @csrf
                <input type="hidden" name="siswa_id" id="modalSiswaId">
                <div class="modal-header">
                    <h5 class="modal-title">Beri Nilai — <span id="modalSiswaName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nilai (0–{{ $praktikum->max_score ?? 100 }}) <span class="text-danger">*</span></label>
                        <input type="number" name="score" id="modalScore" class="form-control"
                               min="0" max="{{ $praktikum->max_score ?? 100 }}" step="0.5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Feedback / Catatan</label>
                        <textarea name="feedback" id="modalFeedback" class="form-control" rows="3"
                                  placeholder="Catatan untuk siswa..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('scoreModal').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('modalSiswaId').value   = btn.dataset.siswaId;
    document.getElementById('modalSiswaName').textContent = btn.dataset.siswaName;
    document.getElementById('modalScore').value     = btn.dataset.score;
    document.getElementById('modalFeedback').value  = btn.dataset.feedback;
});
</script>
@endpush
