<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - LMS Trimurti Husada')</title>
    <meta name="description" content="@yield('description', 'Sistem Manajemen Pembelajaran SMK Kesehatan Trimurti Husada')">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('uploads/logo/favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS 5.3 (match Guru) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS (required by some admin pages) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    <!-- CSS Files -->
    <link href="{{ asset('css/base-layout.css') }}" rel="stylesheet">
    @php($uvers = @filemtime(public_path('css/components/universal.css')))
    <link href="{{ asset('css/components/universal.css') }}?v={{ $uvers }}" rel="stylesheet">
    <link href="{{ asset('css/components/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/form.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin-new.css') }}" rel="stylesheet">

    @stack('css')

    <style>
        /* Admin Layout Specific Styles */
        body.admin-layout {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 0;
        }

        body.admin-layout .main-content,
        body.admin-layout #main-content {
            margin-left: 280px !important;              /* override universal.css (350px) */
            width: calc(100% - 280px) !important;       /* match sidebar width */
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            padding-left: 0 !important;
            padding-right: 0 !important;
            border-left: none !important;
            background: transparent !important;
            position: relative; /* Untuk proper stacking context */
            z-index: 1; /* Di bawah sidebar dan overlay */
        }

        body.admin-layout .main-content.sidebar-collapsed,
        body.admin-layout #main-content.sidebar-collapsed {
            margin-left: 70px !important;
            width: calc(100% - 70px) !important;
        }
        
        /* More specific selectors to override universal.css */
        body.admin-layout #main-content.sidebar-collapsed,
        body.admin-layout .main-content.sidebar-collapsed {
            margin-left: 70px !important;
            width: calc(100% - 70px) !important;
        }
        
        /* Override universal.css with higher specificity */
        body.admin-layout #main-content.sidebar-collapsed.main-content,
        body.admin-layout .main-content.sidebar-collapsed#main-content {
            margin-left: 70px !important;
            width: calc(100% - 70px) !important;
        }
        
        /* Ultra-specific override for universal.css */
        body.admin-layout div#main-content.sidebar-collapsed,
        body.admin-layout div.main-content.sidebar-collapsed {
            margin-left: 70px !important;
            width: calc(100% - 70px) !important;
        }
        
        /* Force override with element type selector */
        body.admin-layout main#main-content.sidebar-collapsed,
        body.admin-layout main.main-content.sidebar-collapsed {
            margin-left: 70px !important;
            width: calc(100% - 70px) !important;
        }

        .content-wrapper {
            padding-top: 70px;
            min-height: 100vh;
            padding-left: 0 !important;
            padding-right: 0 !important;
            background: transparent !important;
        }

        .page-content {
            padding: 1.25rem 1.5rem;
            max-width: none;
            width: 100%;
            margin: 0;
            background: transparent !important;
        }

        /* Guard: remove any accidental inner spacing creating a gutter */
        .page-header, .page-title-section { margin-left: 0 !important; padding-left: 1.5rem; }

        @media (max-width: 768px) {
            body.admin-layout .main-content,
            body.admin-layout #main-content {
                margin-left: 0;
            }
        }
        
        /* Mobile Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1055 !important; /* Di bawah sidebar (1060) tapi di atas universal.css (1050) dan header (999) */
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            display: none;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        @media (max-width: 768px) {
            .sidebar-overlay {
                display: block;
            }
        }
    </style>
</head>
<body class="admin-layout">
    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    @include('partials.sidebar-admin')

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Header -->
        @include('partials.header-admin')

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="page-content">
                @if(View::hasSection('page-title') || View::hasSection('page-actions'))
                <div class="page-header">
                    @hasSection('page-title')
                    <div class="page-title-section">
                        <h1 class="page-title">@yield('page-title')</h1>
                        @hasSection('page-subtitle')
                        <p class="page-description mb-0">@yield('page-subtitle')</p>
                        @endif
                        @if(isset($pageDescription))
                            <p class="page-description mb-0">{{ $pageDescription }}</p>
                        @endif
                    </div>
                    @endif
                    @hasSection('page-actions')
                    <div class="page-actions">
                        @yield('page-actions')
                    </div>
                    @endif
                </div>
                @endif

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Main Content -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('partials.footer')

    <!-- JavaScript Files -->
    <!-- Bootstrap Bundle with Popper (for dropdowns, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery + DataTables (required by some admin pages) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/base-layout.js') }}" defer></script>
    <script src="{{ asset('js/admin.js') }}" defer></script>
    <script src="{{ asset('js/components/Chart.js') }}" defer></script>
    <script src="{{ asset('js/components/Modal.js') }}" defer></script>
    <script src="{{ asset('js/components/FileUpload.js') }}" defer></script>
    <script src="{{ asset('js/notifications.js') }}" defer></script>

    @stack('js')
    @stack('scripts')

    <script>
        // Global admin layout functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.classList.contains('show')) {
                        alert.classList.remove('show');
                        alert.classList.add('fade');
                    }
                }, 5000);
            });

            // Close alert buttons
            const closeButtons = document.querySelectorAll('.btn-close');
            closeButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const alert = this.closest('.alert');
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                });
            });
        });
    </script>
</body>
</html>
