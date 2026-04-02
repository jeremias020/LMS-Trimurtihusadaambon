@extends('layouts.guru')

@section('title', 'Data Absensi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Absensi Siswa</h4>
                    <div class="card-tools">
                        <a href="{{ route('guru.attendance.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Absensi
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Jumlah Hadir</th>
                                    <th>Jumlah Sakit</th>
                                    <th>Jumlah Izin</th>
                                    <th>Jumlah Alpa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attendances as $index => $attendance)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $attendance->tanggal }}</td>
                                        <td>{{ $attendance->kelas->name ?? 'N/A' }}</td>
                                        <td>{{ $attendance->subject->name ?? 'N/A' }}</td>
                                        <td>{{ $attendance->jumlah_hadir ?? 0 }}</td>
                                        <td>{{ $attendance->jumlah_sakit ?? 0 }}</td>
                                        <td>{{ $attendance->jumlah_izin ?? 0 }}</td>
                                        <td>{{ $attendance->jumlah_alpa ?? 0 }}</td>
                                        <td>
                                            <a href="{{ route('guru.attendance.show', $attendance->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('guru.attendance.edit', $attendance->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Belum ada data absensi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection