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
        z-index: 1000;
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
        z-index: 999;
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

    @media (max-width: 768px) {
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
                        @hasSection('page-subtitle')
                            <p class="text-muted mb-0">@yield('page-subtitle')</p>
                        @endif
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
        @include('partials.footer-guru')
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

    @stack('js')

    <script>
        $(document).ready(function() {
            // Enhanced Sidebar toggle functionality
            $('#sidebarToggle, .sidebar-toggle').on('click', function(e) {
                e.preventDefault();
                $('.sidebar').toggleClass('collapsed');
                $('.main-content').toggleClass('expanded');
                
                // Animate toggle button
                $(this).find('i').addClass('fa-spin');
                setTimeout(() => {
                    $(this).find('i').removeClass('fa-spin');
                }, 300);
                
                // Save state in localStorage
                const isCollapsed = $('.sidebar').hasClass('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            });

            // Enhanced Search functionality
            $('#globalSearch').on('focus', function() {
                $('#searchSuggestions').removeClass('d-none');
                $(this).closest('.input-group').addClass('shadow-lg');
            }).on('blur', function(e) {
                setTimeout(() => {
                    if (!$(e.relatedTarget).closest('#searchSuggestions').length) {
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

            // Notification dropdown enhancements
            $('#notificationDropdown').on('shown.bs.dropdown', function() {
                // Mark notifications as viewed (optional)
                console.log('Notifications viewed');
            });

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

            // Mobile sidebar toggle
            $('#mobileSidebarToggle').on('click', function(e) {
                e.preventDefault();
                $('.sidebar').toggleClass('show');
            });

            // Restore sidebar state from localStorage
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (sidebarCollapsed) {
                $('.sidebar').addClass('collapsed');
                $('.main-content').addClass('expanded');
            }

            // Close mobile sidebar when clicking outside
            $(document).on('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!$(e.target).closest('.sidebar, #mobileSidebarToggle').length) {
                        $('.sidebar').removeClass('show');
                    }
                }
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
