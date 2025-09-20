@extends('layouts.app-dashboard')

@section('title', @yield('title', 'Guru Dashboard'))

@section('sidebar')
    <div class="sidebar-header">
        <div class="app-brand">
            <a href="{{ route('guru.dashboard') }}" class="brand-link">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">LMS Trimurti</span>
            </a>
        </div>
    </div>

    <div class="sidebar-body">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ route('guru.dashboard') }}" class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('guru.materials.index') }}" class="nav-link {{ request()->routeIs('guru.materials.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book"></i>
                    <p>Materi</p>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('guru.assignments.index') }}" class="nav-link {{ request()->routeIs('guru.assignments.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tasks"></i>
                    <p>Tugas</p>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('guru.quizzes.index') }}" class="nav-link {{ request()->routeIs('guru.quizzes.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-question-circle"></i>
                    <p>Kuis</p>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('guru.reports.index') }}" class="nav-link {{ request()->routeIs('guru.reports.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    <p>Laporan Nilai</p>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('guru.profile.edit') }}" class="nav-link {{ request()->routeIs('guru.profile.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>Profil Guru</p>
                </a>
            </li>
        </ul>
    </div>
@endsection