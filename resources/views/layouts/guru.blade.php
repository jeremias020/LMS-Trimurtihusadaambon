<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'Dashboard Guru - LMS SMK Kesehatan Trimurti Husada')">
    <title>@yield('title', 'Guru - LMS Trimurti Husada')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('uploads/logo/favicon.ico') }}">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap CSS 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Guru Custom CSS -->
    <link href="{{ asset('css/guru-custom.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
    :root {
        --sidebar-width: 280px;
        --sidebar-collapsed-width: 70px;
        --header-height: 70px;
        --primary-color: #3b82f6;
        --secondary-color: #64748b;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --dark-color: #1e293b;
        --light-color: #f8fafc;
        --border-color: #e2e8f0;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--light-color);
        font-size: 14px;
        line-height: 1.6;
    }

    .main-wrapper {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        width: var(--sidebar-width);
        background: linear-gradient(135deg, var(--dark-color) 0%, #334155 100%);
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 1030; /* Increased to be above header */
        transition: all 0.3s ease;
        overflow-y: auto;
        box-shadow: 4px 0 10px rgba(0,0,0,0.1);
    }

    .sidebar.collapsed {
        width: var(--sidebar-collapsed-width);
    }

    .main-content {
        margin-left: var(--sidebar-width);
        flex: 1;
        transition: all 0.3s ease;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        width: calc(100% - var(--sidebar-width));
        overflow-x: hidden;
    }

    .main-content.expanded {
        margin-left: var(--sidebar-collapsed-width);
        width: calc(100% - var(--sidebar-collapsed-width));
    }

    .top-header {
        background: white;
        height: var(--header-height);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        position: sticky;
        top: 0;
        z-index: 1020; /* Below sidebar but above content */
        border-bottom: 1px solid var(--border-color);
    }

    /* Header Enhancements */
    .btn-ghost {
        background: transparent;
        border: none;
        color: inherit;
        transition: all 0.3s ease;
    }

    .btn-ghost:hover {
        background: rgba(13, 110, 253, 0.1);
        color: var(--primary-color);
        transform: translateY(-1px);
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    /* Search Enhancement */
    .input-group:focus-within {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        border-radius: 50px;
    }

    .search-tag {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .search-tag:hover {
        background-color: var(--primary-color) !important;
        color: white !important;
        transform: translateY(-1px);
    }

    /* Dropdown Enhancements */
    .dropdown-menu {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-radius: 12px;
        padding: 0.5rem 0;
        margin-top: 0.5rem;
    }

    .dropdown-item {
        padding: 0.75rem 1.5rem;
        transition: all 0.2s ease;
        border-radius: 8px;
        margin: 0 0.5rem;
    }

    .dropdown-item:hover {
        background: linear-gradient(135deg, var(--primary-color), #0a58ca);
        color: white;
        transform: translateX(5px);
    }

    /* User Profile Enhancements */
    .dropdown-item.text-danger:hover {
        background: linear-gradient(135deg, var(--danger-color), #c02a2a);
        color: white;
    }

    .content-area {
        flex: 1;
        padding: 1.5rem;
        padding-bottom: 2rem;
        min-height: calc(100vh - var(--header-height) - 200px);
        position: relative; /* Ensure proper stacking context */
        z-index: 1; /* Above overlay but below header */
    }

    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        height: 100%;
    }

    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .card {
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background: #2563eb;
        border-color: #2563eb;
        transform: translateY(-1px);
    }

    /* Footer diluar main-content - CSS baru */
    .main-wrapper > footer {
        width: 100vw !important;
        position: relative !important;
        left: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        z-index: 999;
        box-sizing: border-box;
        clear: both;
    }

    /* Ensure footer is always visible */
    .main-wrapper {
        position: relative;
        overflow-x: hidden;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .main-content {
        flex: 1;
    }

    /* Mobile Sidebar Overlay */
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1025; /* Above header, below sidebar */
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        display: none; /* Hidden on desktop */
    }

    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    @media (max-width: 768px) {
        .sidebar-overlay {
            display: block; /* Show on mobile */
        }
        
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
            width: 100%;
        }

        .content-area {
            padding: 1rem;
            padding-bottom: 1rem;
        }
    }
    </style>

    @stack('css')
</head>
<body>
    <div class="main-wrapper">
        <!-- Mobile Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        @include('partials.sidebar-guru')

        <!-- Main Content -->
        <div class="main-content" id="main-content">
            <!-- Top Header -->
            @include('partials.header-guru')

            <!-- Content Area -->
            <div class="content-area">
                <!-- Breadcrumb -->
                @if(!request()->routeIs('guru.dashboard'))
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        @yield('breadcrumb')
                    </ol>
                </nav>
                @endif

                <!-- Page Header -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h1 class="h3 mb-0 text-dark fw-bold">@yield('page-title', 'Dashboard Guru')</h1>
                        <p class="text-muted mb-0">@yield('page-subtitle', '')</p>
                    </div>
                    <div>
                        @yield('page-actions')
                    </div>
                </div>

                <!-- Main Content -->
                @yield('content')
            </div>
        </div>

        <!-- Footer diluar main-content -->
        @include('partials.footer')
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Notifications JS -->
    <script src="{{ asset('js/notifications.js') }}" defer></script>

    @stack('js')
    @stack('scripts')

    <script>
        // Bootstrap notification data for dynamic rendering in header-guru
        window.__notifData = {
            // eslint-disable-next-line
            // prettier-ignore
            notifications: @json($notifications ?? []),
            unreadCount: {{ $unreadCount ?? 0 }}
        };
        
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $(document).ready(function() {
            // Remove duplicate sidebar toggle functionality - handled in sidebar-guru.blade.php

            // Enhanced Search functionality
            $('#globalSearch').on('focus', function() {
                $('#searchSuggestions').removeClass('d-none');
                $(this).closest('.input-group').addClass('shadow-lg');
            }).on('blur', function(e) {
                setTimeout(() => {
                    if (!$(e.target).closest('#searchSuggestions').length) {
                        $('#searchSuggestions').addClass('d-none');
                        $(this).closest('.input-group').removeClass('shadow-lg');
                    }
                }, 150);
            });

            // Search suggestions click handler
            $('.search-tag').on('click', function() {
                const searchTerm = $(this).data('search');
                $('#globalSearch').val(searchTerm);
                $('#globalSearchForm').submit();
            });

            // Notification dropdown: render dynamic list from server data
            (function renderNotifications(){
                const data = window.__notifData || { notifications: [], unreadCount: 0 };
                const $btn = $('#notificationDropdown');
                if ($btn.length === 0) return;

                // Badge update
                const existingBadge = $btn.find('.badge.rounded-pill');
                if (data.unreadCount > 0) {
                    if (existingBadge.length) {
                        existingBadge.text(data.unreadCount).show();
                    } else {
                        $('<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger pulse-animation" style="font-size: 0.6rem;"></span>')
                            .text(data.unreadCount)
                            .appendTo($btn);
                    }
                } else {
                    existingBadge.remove();
                }

                // Build dropdown content
                const $menu = $btn.closest('.dropdown').find('.dropdown-menu');
                if ($menu.length === 0) return;

                let html = '';
                html += '<li class="dropdown-header d-flex justify-content-between align-items-center py-3">';
                html += '  <div><span class="fw-bold text-dark">Notifikasi</span><div><small class="text-muted">' + (data.unreadCount || 0) + ' notifikasi baru</small></div></div>';
                html += '  <button class="btn btn-sm btn-outline-primary" id="markAllRead" title="Tandai Semua Sudah Dibaca"><i class="fas fa-check-double"></i></button>';
                html += '</li><li><hr class="dropdown-divider m-0"></li>';

                if (data.notifications && data.notifications.length) {
                    data.notifications.forEach(function(n){
                        const title = n.title || n.judul || 'Notifikasi';
                        const content = n.content || n.pesan || '';
                        const createdAt = n.created_at_human || (n.created_at ? new Date(n.created_at).toLocaleString('id-ID') : '');
                        html += '<li>';
                        html += '  <a class="dropdown-item py-3" href="#">';
                        html += '    <div class="d-flex">';
                        html += '      <div class="flex-shrink-0">';
                        html += '        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fas fa-bell text-white"></i></div>';
                        html += '      </div>';
                        html += '      <div class="flex-grow-1 ms-3">';
                        html += '        <div class="fw-medium">' + $('<div>').text(title).html() + '</div>';
                        html += '        <small class="text-muted d-block">' + $('<div>').text(content).html() + '</small>';
                        html += '        <small class="text-muted d-block">' + $('<div>').text(createdAt).html() + '</small>';
                        html += '      </div>';
                        html += '    </div>';
                        html += '  </a>';
                        html += '</li>';
                    });
                } else {
                    html += '<li><div class="px-3 py-4 text-center text-muted"><i class="fas fa-bell-slash fa-lg mb-2"></i><div>Tidak ada notifikasi</div></div></li>';
                }

                html += '<li><hr class="dropdown-divider"></li>';
                html += '<li><a class="dropdown-item text-center py-2" href="#"><small class="fw-medium">Lihat Semua Notifikasi</small></a></li>';

                $menu.html(html);
            })();

            // Notification dropdown enhancements
            $('#notificationDropdown').on('shown.bs.dropdown', function() {
                console.log('Notifications viewed');
            });

            // Remove Praktikum items from header quick actions menu (UI-only)
            (function removePraktikumUI(){
                // Remove any dropdown-item that references Praktikum
                $('.dropdown-menu a:contains("Praktikum"), .dropdown-menu a .fa-flask').closest('li').remove();
                // Remove any quick action cards with flask icon (fallback in case exists elsewhere)
                $('.quick-action-card i.fa-flask').closest('.col-lg-3, .col-md-6, .card, a').remove();
                // Remove any sidebar leftover with flask (safety)
                $('a .fa-flask').closest('a').remove();
            })();

            // Mark all notifications as read
            $('#markAllRead').on('click', function(e) {
                e.preventDefault();
                $(this).html('<i class="fas fa-spinner fa-spin"></i>');

                // Simulate API call
                setTimeout(() => {
                    $(this).html('<i class="fas fa-check"></i>');
                    $('.badge.bg-danger').fadeOut();

                    // Reset after 2 seconds
                    setTimeout(() => {
                        $(this).html('<i class="fas fa-check-double"></i>');
                    }, 2000);
                }, 1000);
            });

            // Sidebar functionality handled in sidebar-guru.blade.php
            // Mobile sidebar toggle
            $('#mobileSidebarToggle').on('click', function(e) {
                e.preventDefault();
                $('.sidebar').toggleClass('show');
                $('#sidebarOverlay').toggleClass('active');
            });

            // Close mobile sidebar when clicking overlay
            $('#sidebarOverlay').on('click', function() {
                $('.sidebar').removeClass('show');
                $('#sidebarOverlay').removeClass('active');
            });

            // Auto-hide alerts after 5 seconds
            $('.alert').delay(5000).fadeOut(300);

            // DataTables initialization
            if ($.fn.DataTable) {
                $('.data-table').DataTable({
                    responsive: true,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
                    }
                });
            }
        });
    </script>
</body>
</html>
