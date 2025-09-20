@extends('layouts.app-dashboard')

@section('title', @yield('title', 'Admin Dashboard'))

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <span>Manajemen User</span>
    </a>
    <a href="{{ route('admin.kelas.index') }}" class="menu-item {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
        <i class="fas fa-school"></i>
        <span>Kelas</span>
    </a>
    <a href="{{ route('admin.jurusan.index') }}" class="menu-item {{ request()->routeIs('admin.jurusan.*') ? 'active' : '' }}">
        <i class="fas fa-graduation-cap"></i>
        <span>Jurusan</span>
    </a>
    <a href="{{ route('admin.kriteria.index') }}" class="menu-item {{ request()->routeIs('admin.kriteria.*') ? 'active' : '' }}">
        <i class="fas fa-clipboard-list"></i>
        <span>Kriteria Penilaian</span>
    </a>
    <a href="{{ route('admin.jadwal-ujian.index') }}" class="menu-item {{ request()->routeIs('admin.jadwal-ujian.*') ? 'active' : '' }}">
        <i class="fas fa-calendar-alt"></i>
        <span>Jadwal Ujian</span>
    </a>
    <a href="{{ route('admin.pengaturan') }}" class="menu-item {{ request()->routeIs('admin.pengaturan') ? 'active' : '' }}">
        <i class="fas fa-cog"></i>
        <span>Pengaturan</span>
    </a>
    <a href="{{ route('logout') }}" class="menu-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
@endsection