@extends('layouts.guru')

@section('title', 'Atur SOP — ' . $practical->title)
@section('page-title', 'Atur SOP')
@section('page-subtitle', $practical->title)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('guru/penilaian') }}" class="text-decoration-none">Penilaian</a></li>
    <li class="breadcrumb-item"><a href="{{ url('guru/penilaian/praktik') }}" class="text-decoration-none">Penilaian Praktik</a></li>
    <li class="breadcrumb-item active">Atur SOP</li>
@endsection

@section('page-actions')
<a href="{{ url('guru/penilaian/praktik') }}" class="btn btn-outline-secondary btn-sm">
    <i class="fas fa-arrow-left me-1"></i>Kembali
</a>
@endsection

@section('content')

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm" style="max-width:750px;">
    <div class="card-header bg-primary text-white py-3">
        <h5 class="mb-0"><i class="fas fa-list-check me-2"></i>Checklist SOP Penilaian Praktik</h5>
    </div>

    {{-- Info praktikum --}}
    <div class="px-4 py-3 bg-light border-bottom d-flex flex-wrap gap-2 align-items-center">
        @if($practical->subject)
            <span class="badge bg-primary">{{ $practical->subject->name }}</span>
        @endif
        @if($practical->kelas)
            <span class="badge bg-secondary">{{ $practical->kelas->name }}</span>
        @endif
    </div>

    <div class="card-body">
        <div class="alert alert-info py-2 small mb-4">
            <i class="fas fa-info-circle me-1"></i>
            Setiap poin SOP memiliki <strong>bobot (%)</strong>. Total semua bobot harus
            <strong>tepat 100%</strong>. Nilai siswa = jumlah bobot poin yang dicentang saat penilaian.
        </div>

        <form action="{{ url('guru/penilaian/praktik/' . $practical->id . '/sop') }}" method="POST" id="sopForm">
            @csrf

            <div id="sopRows">
                @foreach($sopList as $i => $sop)
                <div class="input-group mb-2 sop-row">
                    <span class="input-group-text bg-white text-muted" style="width:2.5rem;">{{ $i + 1 }}</span>
                    <input type="text" class="form-control sop-label"
                           name="sop[{{ $i }}][label]"
                           placeholder="Poin SOP (contoh: Persiapan alat dan bahan)"
                           value="{{ old('sop.' . $i . '.label', $sop['label']) }}" required>
                    <input type="number" class="form-control sop-bobot"
                           name="sop[{{ $i }}][bobot]"
                           style="max-width:90px;"
                           placeholder="%" min="1" max="100"
                           value="{{ old('sop.' . $i . '.bobot', $sop['bobot']) }}"
                           required oninput="updateTotal()">
                    <span class="input-group-text text-muted">%</span>
                    <button type="button" class="btn btn-outline-danger btn-remove"
                            onclick="removeRow(this)" title="Hapus poin">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                @endforeach
            </div>

            {{-- Tambah Baris --}}
            <button type="button" class="btn btn-outline-success btn-sm mt-1 mb-4"
                    onclick="addRow()">
                <i class="fas fa-plus me-1"></i>Tambah Poin SOP
            </button>

            {{-- Total Bobot --}}
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 border mb-4" id="totalBox">
                <div>
                    <span class="text-muted small">Total Bobot:</span>
                    <strong id="totalDisplay" class="fs-5 ms-2">0%</strong>
                </div>
                <div id="totalStatus" class="small"></div>
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-between">
                <a href="{{ url('guru/penilaian/praktik') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save me-1"></i>Simpan SOP
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
var rowCount = {{ count($sopList) }};

function addRow() {
    var i = rowCount++;
    var html = '<div class="input-group mb-2 sop-row">' +
        '<span class="input-group-text bg-white text-muted" style="width:2.5rem;">' + (document.querySelectorAll('.sop-row').length + 1) + '</span>' +
        '<input type="text" class="form-control sop-label" name="sop[' + i + '][label]" placeholder="Poin SOP" required>' +
        '<input type="number" class="form-control sop-bobot" name="sop[' + i + '][bobot]" style="max-width:90px;" placeholder="%" min="1" max="100" required oninput="updateTotal()">' +
        '<span class="input-group-text text-muted">%</span>' +
        '<button type="button" class="btn btn-outline-danger btn-remove" onclick="removeRow(this)" title="Hapus">' +
        '<i class="fas fa-trash"></i></button></div>';
    document.getElementById('sopRows').insertAdjacentHTML('beforeend', html);
    updateTotal();
    renumberRows();
}

function removeRow(btn) {
    btn.closest('.sop-row').remove();
    updateTotal();
    renumberRows();
}

function renumberRows() {
    document.querySelectorAll('.sop-row').forEach(function(row, idx) {
        var num = row.querySelector('.input-group-text');
        if (num) num.textContent = idx + 1;
        // Update name attributes
        var label = row.querySelector('.sop-label');
        var bobot = row.querySelector('.sop-bobot');
        if (label) label.name = 'sop[' + idx + '][label]';
        if (bobot) bobot.name = 'sop[' + idx + '][bobot]';
    });
}

function updateTotal() {
    var total = 0;
    document.querySelectorAll('.sop-bobot').forEach(function(inp) {
        total += parseInt(inp.value) || 0;
    });

    var display = document.getElementById('totalDisplay');
    var status  = document.getElementById('totalStatus');
    var box     = document.getElementById('totalBox');

    display.textContent = total + '%';

    if (total === 100) {
        display.className = 'fs-5 ms-2 text-success fw-bold';
        status.innerHTML  = '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Tepat 100%</span>';
        box.className     = 'd-flex align-items-center gap-3 p-3 rounded-3 border border-success bg-success bg-opacity-5 mb-4';
    } else if (total > 100) {
        display.className = 'fs-5 ms-2 text-danger fw-bold';
        status.innerHTML  = '<span class="badge bg-danger"><i class="fas fa-exclamation me-1"></i>Melebihi 100%</span>';
        box.className     = 'd-flex align-items-center gap-3 p-3 rounded-3 border border-danger bg-danger bg-opacity-5 mb-4';
    } else {
        display.className = 'fs-5 ms-2 text-warning fw-bold';
        status.innerHTML  = '<span class="badge bg-warning text-dark">Sisa: ' + (100 - total) + '%</span>';
        box.className     = 'd-flex align-items-center gap-3 p-3 rounded-3 border mb-4';
    }
}

document.getElementById('sopForm').addEventListener('submit', function(e) {
    var total = 0;
    document.querySelectorAll('.sop-bobot').forEach(function(inp) {
        total += parseInt(inp.value) || 0;
    });
    if (total !== 100) {
        e.preventDefault();
        alert('Total bobot harus 100%. Sekarang: ' + total + '%');
        return;
    }
    var btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan…';
});

// Init
document.addEventListener('DOMContentLoaded', function() { updateTotal(); });
</script>
@endpush
