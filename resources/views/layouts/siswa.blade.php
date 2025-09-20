@extends('layouts.base')

@section('title', 'Siswa - LMS Trimurti Husada')
@section('description', 'Dashboard Siswa - LMS SMK Kesehatan Trimurti Husada')
@section('body-class', 'siswa-layout')

@push('css')
    <link href="{{ asset('css/components/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/form.css') }}" rel="stylesheet">
    <link href="{{ asset('css/siswa.css') }}" rel="stylesheet">
    <link href="{{ asset('css/siswa-custom.css') }}" rel="stylesheet">
@endpush

@section('sidebar')
    @include('partials.sidebar-siswa')
@endsection

@section('header')
    @include('partials.header-siswa')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}">Dashboard</a></li>
    @yield('siswa-breadcrumb')
@endsection

@section('page-title')
    @yield('siswa-page-title', 'Dashboard Siswa')
@endsection

@section('footer')
    @include('partials.footer-siswa')
@endsection

@push('js')
    <script src="{{ asset('js/siswa.js') }}" defer></script>
    <script src="{{ asset('js/components/Chart.js') }}" defer></script>
    <script src="{{ asset('js/components/Modal.js') }}" defer></script>
    <script src="{{ asset('js/components/FileUpload.js') }}" defer></script>
@endpush
