<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - LMS Trimurti Husada')</title>
    <meta name="description" content="@yield('description', 'Sistem Manajemen Pembelajaran SMK Kesehatan Trimurti Husada')">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Files -->
    <link href="{{ asset('css/base-layout.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/universal.css') }}" rel="stylesheet">
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
            margin-left: 280px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        body.admin-layout .main-content.sidebar-collapsed,
        body.admin-layout #main-content.sidebar-collapsed {
            margin-left: 70px;
        }
        
        .content-wrapper {
            padding-top: 70px;
            min-height: 100vh;
        }
        
        .page-content {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        @media (max-width: 768px) {
            body.admin-layout .main-content,
            body.admin-layout #main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="admin-layout">
    <!-- Sidebar -->
    @include('partials.sidebar-admin')

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Header -->
    @include('partials.header-admin')
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="page-content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-title-section">
                        <h1 class="page-title">@yield('page-title', 'Dashboard Admin')</h1>
                        @if(isset($pageDescription))
                            <p class="page-description">{{ $pageDescription }}</p>
                        @endif
                    </div>
                    <div class="page-actions">
                        @yield('page-actions')
                    </div>
                </div>
                
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
    
    <!-- JavaScript Files -->
    <script src="{{ asset('js/base-layout.js') }}" defer></script>
    <script src="{{ asset('js/admin.js') }}" defer></script>
    <script src="{{ asset('js/components/Chart.js') }}" defer></script>
    <script src="{{ asset('js/components/Modal.js') }}" defer></script>
    <script src="{{ asset('js/components/FileUpload.js') }}" defer></script>
    
    @stack('js')
    
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
