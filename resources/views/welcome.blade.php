@extends('layouts.app')

@section('title', 'Beranda - LMS Trimurti Husada')

@section('description', 'Sistem Manajemen Pembelajaran SMK Kesehatan Trimurti Husada Ambon. Akses materi, tugas, praktikum, dan nilai secara online.')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-6">
                <div class="text-white">
                    <h1 class="display-4 fw-bold mb-4 animate-fade-in">
                        Selamat Datang di LMS Trimurti Husada
                    </h1>
                    <p class="lead mb-4 animate-fade-in" style="animation-delay: 0.2s;">
                        Sistem Manajemen Pembelajaran untuk SMK Kesehatan Trimurti Husada Ambon — tempat belajar modern, interaktif, dan terintegrasi.
                    </p>
                    <div class="d-flex gap-3 animate-fade-in" style="animation-delay: 0.4s;">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-home me-2"></i>Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Daftar
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center animate-fade-in" style="animation-delay: 0.6s;">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Logo SMK Kesehatan Trimurti Husada Ambon" 
                         class="img-fluid" 
                         style="max-height: 300px; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));"
                         onerror="this.src='{{ asset('images/default-logo.png') }}'">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold text-primary">Fitur Unggulan</h2>
                <p class="lead text-muted">Platform pembelajaran digital yang lengkap untuk mendukung kegiatan belajar mengajar</p>
            </div>
        </div>
        <div class="row g-4">
            <!-- Feature 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-book-open text-white fa-2x"></i>
                        </div>
                        <h4 class="fw-bold text-primary">Materi Pembelajaran</h4>
                        <p class="text-muted">Akses berbagai materi pembelajaran interaktif dengan format yang beragam seperti video, dokumen, dan presentasi.</p>
                    </div>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-tasks text-white fa-2x"></i>
                        </div>
                        <h4 class="fw-bold text-success">Tugas & Quiz</h4>
                        <p class="text-muted">Kerjakan tugas dan quiz online dengan sistem penilaian otomatis dan feedback real-time dari guru.</p>
                    </div>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-info bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-flask text-white fa-2x"></i>
                        </div>
                        <h4 class="fw-bold text-info">Praktikum Digital</h4>
                        <p class="text-muted">Simulasi praktikum kesehatan dengan teknologi digital untuk pengalaman belajar yang lebih interaktif.</p>
                    </div>
                </div>
            </div>
            
            <!-- Feature 4 -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-warning bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-chart-line text-white fa-2x"></i>
                        </div>
                        <h4 class="fw-bold text-warning">Monitoring Progres</h4>
                        <p class="text-muted">Pantau perkembangan belajar dengan laporan dan analisis yang detail dari setiap aktivitas pembelajaran.</p>
                    </div>
                </div>
            </div>
            
            <!-- Feature 5 -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-danger bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-calendar-check text-white fa-2x"></i>
                        </div>
                        <h4 class="fw-bold text-danger">Absensi Online</h4>
                        <p class="text-muted">Sistem absensi digital yang terintegrasi dengan jadwal pembelajaran dan laporan kehadiran real-time.</p>
                    </div>
                </div>
            </div>
            
            <!-- Feature 6 -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-secondary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-comments text-white fa-2x"></i>
                        </div>
                        <h4 class="fw-bold text-secondary">Komunikasi Interaktif</h4>
                        <p class="text-muted">Diskusi dan komunikasi langsung antara siswa dan guru melalui forum dan chat terintegrasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold mb-2">{{ \App\Models\User::where('role', 'siswa')->count() }}+</h2>
                    <p class="lead mb-0">Siswa Aktif</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold mb-2">{{ \App\Models\User::where('role', 'guru')->count() }}+</h2>
                    <p class="lead mb-0">Guru Profesional</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold mb-2">50+</h2>
                    <p class="lead mb-0">Mata Pelajaran</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold mb-2">100+</h2>
                    <p class="lead mb-0">Materi Digital</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <h2 class="display-5 fw-bold text-primary mb-4">Tentang SMK Kesehatan Trimurti Husada</h2>
                <p class="lead mb-4">SMK Kesehatan Trimurti Husada Ambon adalah institusi pendidikan kesehatan yang berkomitmen menghasilkan tenaga kesehatan profesional dan berkarakter.</p>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-3 fa-lg"></i>
                            <span>Akreditasi Terbaik</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-3 fa-lg"></i>
                            <span>Fasilitas Modern</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-3 fa-lg"></i>
                            <span>Guru Berpengalaman</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-3 fa-lg"></i>
                            <span>Kerjasama Industri</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="{{ asset('images/about-school.jpg') }}" 
                         alt="SMK Trimurti Husada" 
                         class="img-fluid rounded shadow"
                         onerror="this.src='https://via.placeholder.com/600x400/667eea/ffffff?text=SMK+Trimurti+Husada'">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-gradient" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
    <div class="container text-center text-white">
        <h2 class="display-5 fw-bold mb-4">Siap Memulai Pembelajaran Digital?</h2>
        <p class="lead mb-4">Bergabunglah dengan ribuan siswa dan guru yang telah merasakan pengalaman belajar modern di LMS Trimurti Husada.</p>
        @guest
        <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3">
            <i class="fas fa-rocket me-2"></i>Daftar Sekarang
        </a>
        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
            <i class="fas fa-sign-in-alt me-2"></i>Masuk ke Akun
        </a>
        @else
        <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg">
            <i class="fas fa-tachometer-alt me-2"></i>Ke Dashboard
        </a>
        @endguest
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-light py-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h5 class="fw-bold text-primary">LMS Trimurti Husada</h5>
                <p class="mb-1">SMK Kesehatan Trimurti Husada Ambon</p>
                <p class="mb-0">Jl. Dr. Kayadoe, Ambon, Maluku</p>
            </div>
            <div class="col-lg-6 text-lg-end">
                <div class="social-links mb-2">
                    <a href="#" class="text-light me-3"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-youtube fa-lg"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-whatsapp fa-lg"></i></a>
                </div>
                <p class="mb-0">&copy; {{ date('Y') }} LMS Trimurti Husada. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
@push('styles')
<style>
/* Custom animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out forwards;
    opacity: 0;
}

/* Hero section styles */
.min-vh-75 {
    min-height: 75vh;
}

/* Card hover effects */
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.feature-icon {
    transition: all 0.3s ease;
}

.hover-card:hover .feature-icon {
    transform: scale(1.1);
}

/* Statistics animation */
.stat-item {
    opacity: 0;
    animation: fadeIn 1s ease-out forwards;
}

.stat-item:nth-child(1) { animation-delay: 0.1s; }
.stat-item:nth-child(2) { animation-delay: 0.3s; }
.stat-item:nth-child(3) { animation-delay: 0.5s; }
.stat-item:nth-child(4) { animation-delay: 0.7s; }

/* Social links hover */
.social-links a {
    transition: all 0.3s ease;
}

.social-links a:hover {
    transform: translateY(-3px);
    color: #007bff !important;
}

/* Button hover effects */
.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Responsive fixes */
@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .display-5 {
        font-size: 1.5rem;
    }
    
    .min-vh-75 {
        min-height: auto;
        padding: 2rem 0;
    }
}

/* Custom gradient backgrounds */
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Custom shadows */
.custom-shadow {
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Smooth scroll to sections
    $('a[href*="#"]:not([href="#"])').click(function() {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 80
                }, 800);
                return false;
            }
        }
    });
    
    // Counter animation for statistics
    function animateCounters() {
        $('.display-4').each(function() {
            const $counter = $(this);
            const target = parseInt($counter.text().replace('+', ''));
            
            if (!isNaN(target) && target > 0) {
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(function() {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                        $counter.text(current + '+');
                    } else {
                        $counter.text(Math.floor(current) + '+');
                    }
                }, 50);
            }
        });
    }
    
    // Trigger counter animation when statistics section is in view
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    const statsSection = document.querySelector('.bg-primary');
    if (statsSection) {
        observer.observe(statsSection);
    }
    
    // Add loading animation for cards
    $('.hover-card').each(function(index) {
        $(this).delay(index * 100).animate({
            opacity: 1
        }, 500);
    });
    
    // Initialize tooltips if using Bootstrap 5
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>
@endpush
@endsection