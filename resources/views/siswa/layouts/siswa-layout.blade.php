@extends('layouts.app-dashboard')

@section('title', @yield('title', 'Siswa Dashboard'))

@section('sidebar')
    <a href="{{ route('siswa.dashboard') }}" class="menu-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('siswa.materi.index') }}" class="menu-item {{ request()->routeIs('siswa.materi.*') ? 'active' : '' }}">
        <i class="fas fa-book"></i>
        <span>Materi</span>
    </a>
    <a href="{{ route('siswa.quiz.index') }}" class="menu-item {{ request()->routeIs('siswa.quiz.*') ? 'active' : '' }}">
        <i class="fas fa-question-circle"></i>
        <span>Soal/Quiz</span>
    </a>
    <a href="{{ route('siswa.nilai.index') }}" class="menu-item {{ request()->routeIs('siswa.nilai.*') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span>Laporan Nilai</span>
    </a>
    <a href="{{ route('siswa.profile.edit') }}" class="menu-item {{ request()->routeIs('siswa.profile.*') ? 'active' : '' }}">
        <i class="fas fa-user-circle"></i>
        <span>Profil</span>
    </a>
    <a href="{{ route('logout') }}" class="menu-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
@endsection