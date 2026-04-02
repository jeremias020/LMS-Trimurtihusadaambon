@extends('layouts.app')

@section('title', 'Beranda - LMS Trimurti Husada')

@section('description', 'Sistem Manajemen Pembelajaran SMK Kesehatan Trimurti Husada Ambon. Akses materi, tugas, praktikum, dan nilai secara online.')

@section('header')
    @include('partials.landing-header')
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="hero-decor hero-decor-1"></div>
    <div class="hero-decor hero-decor-2"></div>
    <div class="container">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-6">
                <div class="text-white">
                    <div class="d-inline-flex align-items-center gap-2 rounded-pill px-3 py-2 mb-4 hero-pill animate-fade-in">
                        <span class="badge bg-white text-primary">LMS</span>
                        <span class="small">SMK Kesehatan Trimurti Husada Ambon</span>
                    </div>
                    <h1 class="display-4 fw-bold mb-3 animate-fade-in" style="animation-delay: 0.1s;">
                        Belajar Lebih Terarah,
                        <span class="hero-highlight">Praktik Lebih Terukur</span>
                    </h1>
                    <p class="lead mb-4 animate-fade-in" style="animation-delay: 0.2s;">
                        Akses materi, tugas, praktikum, nilai, dan notifikasi ujian dalam satu platform.
                        Lebih cepat, rapi, dan transparan.
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
                        @endauth
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-4 animate-fade-in" style="animation-delay: 0.55s;">
                        <a href="#fitur" class="btn btn-sm btn-outline-light rounded-pill">
                            <i class="fas fa-star me-2"></i>Lihat Fitur
                        </a>
                        <a href="{{ route('about') }}" class="btn btn-sm btn-outline-light rounded-pill">
                            <i class="fas fa-school me-2"></i>Tentang Sekolah
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-sm btn-outline-light rounded-pill">
                            <i class="fas fa-envelope me-2"></i>Kontak
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center animate-fade-in" style="animation-delay: 0.6s;">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Logo SMK Kesehatan Trimurti Husada Ambon" 
                         class="img-fluid" 
                         style="max-height: 320px; filter: drop-shadow(0 14px 28px rgba(0,0,0,0.25));"
                         onerror="this.src='{{ asset('images/default-logo.png') }}'">
                    <div class="row g-3 justify-content-center mt-4">
                        <div class="col-10 col-md-8">
                            <div class="hero-card p-3 p-md-4">
                                <div class="row g-3 text-start">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="hero-card-icon bg-primary-subtle text-primary">
                                                <i class="fas fa-bell"></i>
                                            </div>
                                            <div>
                                                <div class="small text-muted">Notifikasi</div>
                                                <div class="fw-semibold">Ujian & Tugas</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="hero-card-icon bg-success-subtle text-success">
                                                <i class="fas fa-chart-line"></i>
                                            </div>
                                            <div>
                                                <div class="small text-muted">Progress</div>
                                                <div class="fw-semibold">Nilai & Absensi</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="fitur" class="py-5 bg-light">
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

<!-- How It Works Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-5">
                <h2 class="display-6 fw-bold text-primary mb-3">Cara Kerja Singkat</h2>
                <p class="text-muted mb-0">Alur sederhana agar siswa dan guru bisa fokus belajar dan praktik.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="step-card h-100 p-4">
                    <div class="step-number">1</div>
                    <h5 class="fw-bold mb-2">Masuk Akun</h5>
                    <p class="text-muted mb-0">Login sesuai role: admin, guru, atau siswa.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="step-card h-100 p-4">
                    <div class="step-number">2</div>
                    <h5 class="fw-bold mb-2">Akses Materi & Tugas</h5>
                    <p class="text-muted mb-0">Materi dan soal tersusun berdasarkan kelas dan mata pelajaran.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="step-card h-100 p-4">
                    <div class="step-number">3</div>
                    <h5 class="fw-bold mb-2">Praktik & Penilaian</h5>
                    <p class="text-muted mb-0">Penilaian praktik berbasis kriteria dan bobot yang jelas.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="step-card h-100 p-4">
                    <div class="step-number">4</div>
                    <h5 class="fw-bold mb-2">Laporan & Notifikasi</h5>
                    <p class="text-muted mb-0">Pantau progres nilai, absensi, dan pengingat ujian secara cepat.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-primary text-white stats-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold mb-2">{{ $stats['siswa'] ?? 0 }}+</h2>
                    <p class="lead mb-0">Siswa Aktif</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold mb-2">{{ $stats['guru'] ?? 0 }}+</h2>
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

<!-- Program Keahlian Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-5">
                <h2 class="display-6 fw-bold text-primary mb-3">Program Keahlian</h2>
                <p class="text-muted mb-0">Fokus pada kompetensi kesehatan yang relevan dengan kebutuhan industri.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="program-icon bg-primary bg-gradient text-white">
                                <i class="fas fa-user-nurse"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Keperawatan</div>
                                <div class="text-muted small">Kompetensi dasar & lanjutan</div>
                            </div>
                        </div>
                        <div class="text-muted">Materi terstruktur, praktikum terukur, dan laporan nilai praktik yang transparan.</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="program-icon bg-success bg-gradient text-white">
                                <i class="fas fa-pills"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Farmasi</div>
                                <div class="text-muted small">Peracikan & K3</div>
                            </div>
                        </div>
                        <div class="text-muted">Penilaian berbasis SOP checklist, bobot kriteria, dan feedback otomatis.</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="program-icon bg-info bg-gradient text-white">
                                <i class="fas fa-microscope"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Analis Kesehatan</div>
                                <div class="text-muted small">Lab & pemeriksaan dasar</div>
                            </div>
                        </div>
                        <div class="text-muted">Pendokumentasian nilai dan absensi membantu evaluasi progres kompetensi siswa.</div>
                    </div>
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

<!-- FAQ Section -->
<section id="faq" class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-5">
                <h2 class="display-6 fw-bold text-primary mb-3">Pertanyaan Umum</h2>
                <p class="text-muted mb-0">Jawaban singkat untuk hal yang paling sering ditanyakan.</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header" id="faqOne">
                            <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseOne" aria-expanded="true" aria-controls="faqCollapseOne">
                                Bagaimana cara siswa melihat nilai praktik?
                            </button>
                        </h2>
                        <div id="faqCollapseOne" class="accordion-collapse collapse show" aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Setelah guru memfinalkan penilaian, nilai total, grade, dan feedback otomatis akan tersimpan dan dapat dilihat di laporan nilai pada akun siswa.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header" id="faqTwo">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseTwo" aria-expanded="false" aria-controls="faqCollapseTwo">
                                Apakah tugas bisa dikumpulkan lewat HP?
                            </button>
                        </h2>
                        <div id="faqCollapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Bisa. Halaman dirancang responsif sehingga siswa dapat login dan mengumpulkan tugas melalui perangkat mobile.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 shadow-sm">
                        <h2 class="accordion-header" id="faqThree">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseThree" aria-expanded="false" aria-controls="faqCollapseThree">
                                Bagaimana sistem menghitung nilai praktik?
                            </button>
                        </h2>
                        <div id="faqCollapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Skor 1–4 dikonversi menjadi persen (0–100), lalu dikalikan bobot kriteria (misalnya 45 berarti 45%), kemudian dijumlahkan untuk menghasilkan total nilai 0–100.
                            </div>
                        </div>
                    </div>
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
        <a href="{{ route('login') }}" class="btn btn-light btn-lg">
            <i class="fas fa-sign-in-alt me-2"></i>Masuk ke Akun
        </a>
        @else
        <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg me-2">
            <i class="fas fa-tachometer-alt me-2"></i>Ke Dashboard
        </a>
        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
            <i class="fas fa-headset me-2"></i>Bantuan
        </a>
        @endguest
    </div>
</section>
@push('css')
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

.hero-pill {
    background: rgba(255, 255, 255, 0.14);
    border: 1px solid rgba(255, 255, 255, 0.22);
    backdrop-filter: blur(10px);
}

.hero-highlight {
    position: relative;
    display: inline-block;
}

.hero-highlight::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0.15em;
    width: 100%;
    height: 0.35em;
    background: rgba(255, 255, 255, 0.24);
    border-radius: 999px;
    z-index: -1;
}

.hero-decor {
    position: absolute;
    border-radius: 999px;
    filter: blur(0px);
    opacity: 0.18;
    pointer-events: none;
}

.hero-decor-1 {
    width: 420px;
    height: 420px;
    background: #ffffff;
    top: -180px;
    right: -180px;
}

.hero-decor-2 {
    width: 320px;
    height: 320px;
    background: #000000;
    bottom: -160px;
    left: -160px;
    opacity: 0.10;
}

.hero-card {
    background: rgba(255, 255, 255, 0.92);
    border: 1px solid rgba(255, 255, 255, 0.6);
    border-radius: 16px;
    box-shadow: 0 18px 60px rgba(0,0,0,0.18);
}

.hero-card-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
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

/* Step cards */
.step-card {
    background: #ffffff;
    border: 1px solid rgba(0,0,0,0.06);
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.step-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 45px rgba(0,0,0,0.10);
}

.step-number {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    margin-bottom: 14px;
}

/* Program section */
.program-icon {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Accordion tweaks */
.accordion-button {
    border-radius: 14px !important;
}

.accordion-item {
    border-radius: 14px;
    overflow: hidden;
}
</style>
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Smooth scroll to sections
    document.querySelectorAll('a[href^="#"]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            const href = link.getAttribute('href');
            if (!href || href === '#') return;

            const target = document.querySelector(href);
            if (!target) return;

            e.preventDefault();
            const top = target.getBoundingClientRect().top + window.pageYOffset - 80;
            window.scrollTo({ top, behavior: 'smooth' });
        });
    });

    // Counter animation for statistics
    function animateCounters() {
        document.querySelectorAll('.stats-section .display-4').forEach(function (counterEl) {
            const raw = (counterEl.textContent || '').replace('+', '').trim();
            const target = parseInt(raw, 10);
            if (Number.isNaN(target) || target <= 0) return;

            let current = 0;
            const steps = 50;
            const increment = target / steps;
            const timer = setInterval(function () {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                counterEl.textContent = Math.floor(current) + '+';
            }, 30);
        });
    }

    // Trigger counter animation when statistics section is in view
    const statsSection = document.querySelector('.stats-section');
    if (statsSection && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        observer.observe(statsSection);
    } else {
        animateCounters();
    }

    // Initialize tooltips if using Bootstrap 5
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>
@endpush
@endsection