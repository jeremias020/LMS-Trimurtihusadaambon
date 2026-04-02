@extends('layouts.siswa')

@section('title', 'Laporan & Nilai')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Laporan & Nilai Saya</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-tasks"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Tugas</span>
                                    <span class="info-box-number">{{ $totalAssignments }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tugas Dinilai</span>
                                    <span class="info-box-number">{{ $gradedAssignments }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-flask"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Praktikum</span>
                                    <span class="info-box-number">{{ $totalPracticals }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-calendar-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Kehadiran</span>
                                    <span class="info-box-number">{{ $presentAttendances }}/{{ $totalAttendances }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Nilai Tugas</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tugas</th>
                                            <th>Mapel</th>
                                            <th>Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($assignmentSubmissions as $submission)
                                            <tr>
                                                <td>{{ $submission->assignment->title }}</td>
                                                <td>{{ $submission->assignment->subject->name }}</td>
                                                <td>
                                                    @if($submission->score)
                                                        <span class="badge badge-success">{{ $submission->score }}</span>
                                                    @else
                                                        <span class="badge badge-secondary">Belum dinilai</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">Belum ada tugas</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Nilai Praktikum</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Praktikum</th>
                                            <th>Mapel</th>
                                            <th>Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($practicalScores as $score)
                                            <tr>
                                                <td>{{ $score->practical->title }}</td>
                                                <td>{{ $score->practical->subject->name }}</td>
                                                <td>
                                                    @if($score->score)
                                                        <span class="badge badge-success">{{ $score->score }}</span>
                                                    @else
                                                        <span class="badge badge-secondary">Belum dinilai</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">Belum ada praktikum</td>
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
    </div>
</div>
@endsection