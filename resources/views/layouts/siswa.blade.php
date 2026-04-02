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

@push('css')
<style>
:root{--sidebar-width:280px;--sidebar-collapsed-width:70px;--header-height:70px;--primary-color:#3b82f6;--secondary-color:#64748b;--success-color:#10b981;--warning-color:#f59e0b;--danger-color:#ef4444;--dark-color:#1e293b;--light-color:#f8fafc;--border-color:#e2e8f0}
body{font-family:'Inter',sans-serif;background-color:var(--light-color);font-size:14px;line-height:1.6}
.main-wrapper{display:flex;min-height:100vh;position:relative;overflow-x:hidden;flex-direction:column}
.sidebar{width:var(--sidebar-width);background:linear-gradient(135deg,var(--dark-color) 0%,#334155 100%);color:#fff;position:fixed;top:0;left:0;height:100vh;z-index:1000;transition:all .3s ease;overflow-y:auto;box-shadow:4px 0 10px rgba(0,0,0,.1)}
.sidebar.collapsed{width:var(--sidebar-collapsed-width)}
.main-content{margin-left:var(--sidebar-width);flex:1;transition:all .3s ease;min-height:100vh;display:flex;flex-direction:column;width:calc(100% - var(--sidebar-width))}
.main-content.expanded{margin-left:var(--sidebar-collapsed-width);width:calc(100% - var(--sidebar-collapsed-width))}
.top-header{background:#fff;height:var(--header-height);display:flex;align-items:center;justify-content:space-between;padding:0 1.5rem;box-shadow:0 2px 10px rgba(0,0,0,.08);position:sticky;top:0;z-index:999;border-bottom:1px solid var(--border-color)}
.btn-ghost{background:transparent;border:none;color:inherit;transition:all .3s ease}
.btn-ghost:hover{background:rgba(13,110,253,.1);color:var(--primary-color);transform:translateY(-1px)}
.content-area{flex:1;padding:1.5rem;padding-bottom:2rem;min-height:calc(100vh - var(--header-height) - 200px)}
.stats-card{background:#fff;border-radius:12px;padding:1.5rem;border:1px solid var(--border-color);transition:all .3s ease;height:100%}
.stats-card:hover{transform:translateY(-4px);box-shadow:0 8px 25px rgba(0,0,0,.1)}
.card{border:1px solid var(--border-color);border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.06)}
.btn{border-radius:8px;font-weight:500;padding:.5rem 1rem;transition:all .3s ease}
.btn-primary{background:var(--primary-color);border-color:var(--primary-color)}
.btn-primary:hover{background:#2563eb;border-color:#2563eb;transform:translateY(-1px)}
@media (max-width:768px){.sidebar{transform:translateX(-100%)}.sidebar.show{transform:translateX(0)}.main-content{margin-left:0;width:100%}.content-area{padding:1rem;padding-bottom:1rem}}
</style>
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

@push('js')
    <script src="{{ asset('js/siswa.js') }}" defer></script>
    <script src="{{ asset('js/components/Chart.js') }}" defer></script>
    <script src="{{ asset('js/components/Modal.js') }}" defer></script>
    <script src="{{ asset('js/components/FileUpload.js') }}" defer></script>
    <script src="{{ asset('js/notifications.js') }}" defer></script>
    <script>
    document.addEventListener('DOMContentLoaded',function(){
        // Bootstrap notification data for student header
        window.__notifData = {
            notifications: @json($notifications ?? []),
            unreadCount: {{ isset($notifications) ? count($notifications) : 0 }}
        };

        var toggle=document.getElementById('sidebarToggle');
        if(toggle){
            toggle.addEventListener('click',function(e){
                e.preventDefault();
                var sidebar=document.querySelector('.sidebar');
                var main=document.querySelector('.main-content');
                if(sidebar&&main){
                    sidebar.classList.toggle('collapsed');
                    main.classList.toggle('expanded');
                    var isCollapsed=sidebar.classList.contains('collapsed');
                    try{localStorage.setItem('sidebarCollapsed',isCollapsed);}catch(err){}
                }
            });
        }
        try{var saved=localStorage.getItem('sidebarCollapsed')==='true';if(saved){var s=document.querySelector('.sidebar');var m=document.querySelector('.main-content');if(s&&m){s.classList.add('collapsed');m.classList.add('expanded');}}}catch(err){}
        document.querySelectorAll('.alert').forEach(function(el){setTimeout(function(){el.style.display='none';},5000);});

        // Render notifications into header dropdown
        (function renderNotifications(){
            var data = window.__notifData || { notifications: [], unreadCount: 0 };
            var btn = document.getElementById('notificationDropdown');
            if(!btn) return;

            // Badge
            var existing = btn.querySelector('.badge.rounded-pill');
            if(data.unreadCount > 0){
                if(!existing){
                    var b = document.createElement('span');
                    b.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                    b.style.fontSize = '0.6rem';
                    b.textContent = data.unreadCount;
                    btn.appendChild(b);
                } else {
                    existing.textContent = data.unreadCount;
                    existing.style.display = '';
                }
            } else if(existing){
                existing.remove();
            }

            // Menu
            var dropdown = btn.closest('.dropdown');
            if(!dropdown) return;
            var menu = dropdown.querySelector('.dropdown-menu');
            if(!menu) return;

            var html = '';
            html += '<li class="dropdown-header d-flex justify-content-between align-items-center py-3">';
            html += '  <div><span class="fw-bold text-dark">Notifikasi</span><div><small class="text-muted">' + (data.unreadCount || 0) + ' notifikasi baru</small></div></div>';
            html += '</li><li><hr class="dropdown-divider m-0"></li>';

            if(data.notifications && data.notifications.length){
                data.notifications.forEach(function(n){
                    var title = n.title || n.judul || 'Notifikasi';
                    var content = n.content || n.pesan || '';
                    var time = n.created_at_human || '';
                    html += '<li>';
                    html += '  <a class="dropdown-item py-3" href="#">';
                    html += '    <div class="d-flex">';
                    html += '      <div class="flex-shrink-0">';
                    html += '        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"><i class="fas fa-bell text-white"></i></div>';
                    html += '      </div>';
                    html += '      <div class="flex-grow-1 ms-3">';
                    html += '        <div class="fw-medium">' + title.replace(/</g,'&lt;').replace(/>/g,'&gt;') + '</div>';
                    html += '        <small class="text-muted d-block">' + String(content).replace(/</g,'&lt;').replace(/>/g,'&gt;') + '</small>';
                    html += '        <small class="text-muted d-block">' + String(time).replace(/</g,'&lt;').replace(/>/g,'&gt;') + '</small>';
                    html += '      </div>';
                    html += '    </div>';
                    html += '  </a>';
                    html += '</li>';
                });
            } else {
                html += '<li><div class="px-3 py-4 text-center text-muted"><i class="fas fa-bell-slash fa-lg mb-2"></i><div>Tidak ada notifikasi</div></div></li>';
            }

            menu.innerHTML = html;
        })();
    });
    </script>
@endpush
