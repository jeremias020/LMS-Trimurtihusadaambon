@extends('layouts.guru')

@section('title', 'Nilai Praktik — ' . $practical->title)
@section('page-title', 'Nilai Praktik')
@section('page-subtitle', $practical->title)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('guru/penilaian') }}" class="text-decoration-none">Penilaian</a></li>
    <li class="breadcrumb-item"><a href="{{ url('guru/penilaian/praktik') }}" class="text-decoration-none">Penilaian Praktik</a></li>
    <li class="breadcrumb-item active">Nilai</li>
@endsection

@section('page-actions')
<a href="{{ url('guru/penilaian/praktik') }}" class="btn btn-outline-secondary btn-sm">
    <i class="fas fa-arrow-left me-1"></i>Kembali
</a>
@endsection

@section('content')

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Info Praktikum --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row g-2 align-items-center">
            <div class="col-md-6">
                <h6 class="fw-semibold mb-1">{{ $practical->title }}</h6>
                <div class="d-flex flex-wrap gap-2">
                    @if($practical->subject)
                        <span class="badge bg-primary">{{ $practical->subject->name }}</span>
                    @endif
                    @if($practical->kelas)
                        <span class="badge bg-secondary">{{ $practical->kelas->name }}</span>
                    @endif
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <small class="text-muted">
                    <i class="fas fa-list-check me-1"></i>
                    <strong>{{ count($sopList) }}</strong> poin SOP ·
                    Total bobot: <strong>{{ array_sum(array_column($sopList, 'bobot')) }}%</strong>
                </small>
            </div>
        </div>
    </div>
</div>

@if(empty($sopList))
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle me-2"></i>
    SOP belum diatur untuk praktikum ini.
    <a href="{{ url('guru/penilaian/praktik') }}" class="alert-link">Kembali dan atur SOP terlebih dahulu.</a>
</div>
@elseif($siswaList->isEmpty())
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    Tidak ada siswa aktif di kelas ini.
</div>
@else

{{-- Cara baca tabel --}}
<div class="alert alert-light border small mb-4">
    <i class="fas fa-info-circle text-primary me-1"></i>
    <strong>Cara penilaian:</strong> Centang poin SOP yang <strong>terpenuhi</strong> oleh siswa.
    Nilai akan dihitung otomatis dari jumlah bobot poin yang dicentang.
    Klik <strong>"Centang Semua"</strong> untuk siswa yang memenuhi semua poin.
</div>

<form action="{{ url('guru/penilaian/praktik/' . $practical->id . '/nilai') }}" method="POST" id="nilaiForm">
    @csrf

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0 fw-semibold">
                Daftar Siswa
                <span class="badge bg-secondary ms-1">{{ $siswaList->count() }}</span>
            </h6>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-success btn-sm" onclick="checkAllSiswa()">
                    <i class="fas fa-check-double me-1"></i>Semua Poin Semua Siswa
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="uncheckAll()">
                    <i class="fas fa-times me-1"></i>Kosongkan Semua
                </button>
            </div>
        </div>

        {{-- Header SOP --}}
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0" id="nilaiTable">
                <thead class="table-light">
                    <tr>
                        <th rowspan="2" style="min-width:160px;">Siswa</th>
                        <th colspan="{{ count($sopList) }}" class="text-center bg-primary bg-opacity-10 text-primary">
                            Poin SOP (centang = terpenuhi)
                        </th>
                        <th rowspan="2" class="text-center" style="min-width:80px;">Nilai</th>
                        <th rowspan="2" class="text-center" style="min-width:60px;">Grade</th>
                        <th rowspan="2" class="text-center" style="min-width:80px;">Aksi</th>
                    </tr>
                    <tr>
                        @foreach($sopList as $sop)
                        <th class="text-center small" style="min-width:100px;" title="{{ $sop['label'] }}">
                            <div class="text-truncate" style="max-width:90px;">{{ $sop['label'] }}</div>
                            <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-25" style="font-size:10px;">
                                {{ $sop['bobot'] }}%
                            </span>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswaList as $i => $siswa)
                    <tr id="row-{{ $i }}" class="{{ $siswa->sudah_dinilai ? '' : 'table-warning bg-opacity-25' }}">
                        <td>
                            <input type="hidden" name="penilaian[{{ $i }}][siswa_id]" value="{{ $siswa->user_id }}">
                            <div class="fw-semibold" style="font-size:13px;">{{ $siswa->user?->name ?? '—' }}</div>
                            <small class="text-muted">{{ $siswa->nis ?? '' }}</small>
                            @if($siswa->sudah_dinilai)
                                <span class="badge bg-success d-block mt-1" style="font-size:10px;">
                                    Nilai: {{ $siswa->nilai_score }}
                                </span>
                            @endif
                        </td>

                        @foreach($sopList as $j => $sop)
                        <td class="text-center">
                            <div class="form-check d-flex justify-content-center">
                                <input type="checkbox"
                                       class="form-check-input sop-check sop-check-{{ $i }}"
                                       name="penilaian[{{ $i }}][checked][]"
                                       value="{{ $sop['label'] }}"
                                       data-bobot="{{ $sop['bobot'] }}"
                                       data-row="{{ $i }}"
                                       onchange="recalcNilai({{ $i }})"
                                       {{ in_array($sop['label'], $siswa->checked_sop ?? []) ? 'checked' : '' }}>
                            </div>
                        </td>
                        @endforeach

                        <td class="text-center fw-bold" id="nilai-{{ $i }}">
                            {{ $siswa->sudah_dinilai ? $siswa->nilai_score : '0' }}
                        </td>
                        <td class="text-center" id="grade-{{ $i }}">
                            @if($siswa->sudah_dinilai)
                                @php $g = $siswa->nilai_score >= 90 ? 'A' : ($siswa->nilai_score >= 80 ? 'B' : ($siswa->nilai_score >= 70 ? 'C' : ($siswa->nilai_score >= 60 ? 'D' : 'E'))); @endphp
                                <span class="badge bg-{{ $g === 'A' ? 'success' : ($g === 'B' ? 'primary' : ($g === 'C' ? 'info' : ($g === 'D' ? 'warning' : 'danger'))) }}">{{ $g }}</span>
                            @else
                                <span class="badge bg-secondary">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-outline-success btn-sm"
                                    onclick="checkAllRow({{ $i }})" title="Centang semua poin">
                                <i class="fas fa-check-double"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ url('guru/penilaian/praktik') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
        <button type="submit" class="btn btn-primary" id="submitBtn">
            <i class="fas fa-save me-1"></i>Simpan Semua Penilaian
        </button>
    </div>
</form>
@endif

@endsection

@push('scripts')
<script>
function recalcNilai(rowIdx) {
    var total = 0;
    document.querySelectorAll('.sop-check-' + rowIdx).forEach(function(cb) {
        if (cb.checked) total += parseInt(cb.dataset.bobot) || 0;
    });
    total = Math.min(total, 100);

    document.getElementById('nilai-' + rowIdx).textContent = total;

    var g, cls;
    if (total >= 90)      { g = 'A'; cls = 'bg-success'; }
    else if (total >= 80) { g = 'B'; cls = 'bg-primary'; }
    else if (total >= 70) { g = 'C'; cls = 'bg-info text-dark'; }
    else if (total >= 60) { g = 'D'; cls = 'bg-warning text-dark'; }
    else                  { g = 'E'; cls = 'bg-danger'; }

    var gradeEl = document.getElementById('grade-' + rowIdx);
    gradeEl.innerHTML = '<span class="badge ' + cls + '">' + g + '</span>';

    var row = document.getElementById('row-' + rowIdx);
    if (total > 0) row.classList.remove('table-warning');
    else row.classList.add('table-warning', 'bg-opacity-25');
}

function checkAllRow(rowIdx) {
    document.querySelectorAll('.sop-check-' + rowIdx).forEach(function(cb) { cb.checked = true; });
    recalcNilai(rowIdx);
}

function checkAllSiswa() {
    document.querySelectorAll('.sop-check').forEach(function(cb) { cb.checked = true; });
    var rows = new Set();
    document.querySelectorAll('.sop-check').forEach(function(cb) { rows.add(cb.dataset.row); });
    rows.forEach(function(r) { recalcNilai(r); });
}

function uncheckAll() {
    document.querySelectorAll('.sop-check').forEach(function(cb) { cb.checked = false; });
    var rows = new Set();
    document.querySelectorAll('.sop-check').forEach(function(cb) { rows.add(cb.dataset.row); });
    rows.forEach(function(r) { recalcNilai(r); });
}

document.addEventListener('DOMContentLoaded', function() {
    var rows = new Set();
    document.querySelectorAll('.sop-check').forEach(function(cb) { rows.add(cb.dataset.row); });
    rows.forEach(function(r) { recalcNilai(r); });
});

document.getElementById('nilaiForm')?.addEventListener('submit', function() {
    var btn = document.getElementById('submitBtn');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan…';
    }
});
</script>
@endpush
