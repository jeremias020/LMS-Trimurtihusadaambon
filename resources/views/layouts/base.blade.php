<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'LMS SMK Kesehatan Trimurti Husada')">
    <title>@yield('title', 'LMS Trimurti Husada')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('uploads/logo/favicon.ico') }}">

    <!-- Font Awesome 6 (Consistent version) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts (Consistent font) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS 5.3 (Consistent version) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/components/universal.css') }}" rel="stylesheet">
    <link href="{{ asset('css/base-layout.css') }}" rel="stylesheet">
    @stack('css')
</head>
<body class="@yield('body-class', 'lms-layout')">
    <div class="main-wrapper">
        <!-- Sidebar -->
        @yield('sidebar')

        <!-- Main Content Wrapper -->
        <div class="main-content" id="main-content">
            <!-- Header -->
            @yield('header')

            <!-- Content Area -->
            <div class="content-area" style="padding: 1.5rem;">
                <!-- Page Header -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h1 class="h3 mb-0 fw-bold">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-muted mb-0">@yield('page-subtitle', '')</p>
                    </div>
                    <div>
                        @yield('page-actions')
                    </div>
                </div>

                <!-- Flash Messages -->
                @include('partials.flash-messages')

                <!-- Main Content -->
                @yield('content')
            </div>
        </div>

        <!-- Footer (Outside main-content for consistency) -->
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

    <!-- Base JavaScript -->
    <script src="{{ asset('js/base-layout.js') }}"></script>
    <script src="{{ asset('js/notifications.js') }}" defer></script>

    @stack('js')

    <script>
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Enhanced Sidebar toggle functionality for new admin sidebar
            $('#sidebarToggle, .sidebar-toggle, #sidebarCollapse, #mobileSidebarToggle').on('click', function(e) {
                e.preventDefault();

                // Handle new sidebar structure
                if ($('.sidebar-wrapper').length) {
                    $('.sidebar-wrapper').toggleClass('collapsed');
                    $('#main-content, .main-content').toggleClass('sidebar-collapsed');

                    // Update toggle icon for new structure
                    const icon = $(this).find('i');
                    const isCollapsed = $('.sidebar-wrapper').hasClass('collapsed');
                    if (isCollapsed) {
                        icon.removeClass('fa-bars').addClass('fa-arrow-right');
                    } else {
                        icon.removeClass('fa-arrow-right').addClass('fa-bars');
                    }

                    localStorage.setItem('admin-sidebar-collapsed', isCollapsed);
                } else {
                    // Fallback for old sidebar structure
                    $('.sidebar').toggleClass('collapsed');
                    $('.main-content').toggleClass('expanded');

                    const isCollapsed = $('.sidebar').hasClass('collapsed');
                    localStorage.setItem('sidebarCollapsed', isCollapsed);
                }

                // Animate toggle button
                $(this).find('i').addClass('fa-spin');
                setTimeout(() => {
                    $(this).find('i').removeClass('fa-spin');
                }, 300);

                // Trigger resize for charts
                setTimeout(() => {
                    window.dispatchEvent(new Event('resize'));
                }, 300);
            });

            // Restore sidebar state from localStorage
            if ($('.sidebar-wrapper').length) {
                const sidebarCollapsed = localStorage.getItem('admin-sidebar-collapsed') === 'true';
                if (sidebarCollapsed) {
                    $('.sidebar-wrapper').addClass('collapsed');
                    $('#main-content, .main-content').addClass('sidebar-collapsed');
                    $('#sidebarToggle i, .sidebar-toggle i').removeClass('fa-bars').addClass('fa-arrow-right');
                }
            } else {
                const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (sidebarCollapsed) {
                    $('.sidebar').addClass('collapsed');
                    $('.main-content').addClass('expanded');
                }
            }

            // Close mobile sidebar when clicking outside
            $(document).on('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!$(e.target).closest('.sidebar, .sidebar-wrapper, #mobileSidebarToggle, .sidebar-toggle').length) {
                        $('.sidebar, .sidebar-wrapper').removeClass('show mobile-visible');
                        $('.sidebar-overlay').removeClass('active');
                        $('body').removeClass('sidebar-open');
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
