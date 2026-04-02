@extends('layouts.app-dashboard')

@section('title')
    @yield('title', 'Guru Dashboard')
@endsection

@section('sidebar')
    @include('partials.sidebar-guru')
@endsection