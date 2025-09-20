<footer class="mt-auto bg-gradient-to-r from-gray-50 to-gray-100 border-top border-primary border-opacity-20 py-4">
    <div class="container-fluid">
        <!-- Main Footer Content -->
        <div class="row align-items-center mb-3">
            <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-circle p-2 me-3">
                        <i class="fas fa-user-graduate text-white"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">LMS Student Portal</div>
                        <small class="text-muted">Portal Pembelajaran Siswa</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                <div class="text-center">
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('siswa.materials.index') }}" class="btn btn-outline-primary btn-sm" title="Materi Pembelajaran">
                            <i class="fas fa-book me-1"></i>
                            <span class="d-none d-sm-inline">Materi</span>
                        </a>
                        <a href="{{ route('siswa.assignments.index') }}" class="btn btn-outline-success btn-sm" title="Tugas Saya">
                            <i class="fas fa-tasks me-1"></i>
                            <span class="d-none d-sm-inline">Tugas</span>
                        </a>
                        <a href="{{ route('siswa.nilai.index') }}" class="btn btn-outline-info btn-sm" title="Nilai Saya">
                            <i class="fas fa-chart-line me-1"></i>
                            <span class="d-none d-sm-inline">Nilai</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="text-lg-end text-center">
                    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-lg-end gap-2">
                        <!-- Student Status -->
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-pill px-2 py-1 me-2">
                                <i class="fas fa-user-check text-success me-1"></i>
                                <small class="text-success fw-medium">Active</small>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-graduation-cap me-1"></i>Student v1.0.0
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Student Progress Row -->
        <div class="row align-items-center mb-3 py-2 bg-light rounded">
            <div class="col-md-3 text-center">
                <small class="text-muted d-block">Tugas Selesai</small>
                <span class="fw-bold text-success">{{ $progress['completed_assignments'] ?? '12' }}/{{ $progress['total_assignments'] ?? '15' }}</span>
            </div>
            <div class="col-md-3 text-center">
                <small class="text-muted d-block">Nilai Rata-rata</small>
                <span class="fw-bold text-primary">{{ $progress['average_grade'] ?? '8.2' }}</span>
            </div>
            <div class="col-md-3 text-center">
                <small class="text-muted d-block">Kehadiran</small>
                <span class="fw-bold text-info">{{ $progress['attendance_rate'] ?? '92%' }}</span>
            </div>
            <div class="col-md-3 text-center">
                <small class="text-muted d-block">Progress</small>
                <div class="progress mx-auto" style="width: 60px; height: 6px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress['completion_rate'] ?? '85' }}%"></div>
                </div>
                <small class="fw-medium text-success">{{ $progress['completion_rate'] ?? '85' }}%</small>
            </div>
        </div>
        
        <!-- Quick Study Links -->
        <div class="row align-items-center mb-3 py-2 bg-white rounded border">
            <div class="col-12">
                <div class="text-center">
                    <small class="text-muted fw-medium d-block mb-2">AKSES CEPAT BELAJAR</small>
                    <div class="d-flex justify-content-center flex-wrap gap-2">
                        <a href="{{ route('siswa.materials.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-book me-1"></i>Buka Materi
                        </a>
                        <a href="{{ route('siswa.assignments.index', ['filter' => 'pending']) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-clock me-1"></i>Tugas Pending
                        </a>
                        <a href="{{ route('siswa.praktikum.index') }}" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-flask me-1"></i>Jadwal Praktikum
                        </a>
                        <a href="{{ route('siswa.absensi.index') }}" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-calendar-check me-1"></i>Riwayat Absensi
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Copyright Row -->
        <hr class="border-primary border-opacity-20">
        <div class="row align-items-center">
            <div class="col-md-6">
                <small class="text-muted">
                    &copy; {{ date('Y') }} 
                    <strong class="text-primary">SMK Kesehatan Trimurti Husada Ambon</strong>. 
                    All rights reserved.
                </small>
            </div>
            <div class="col-md-6">
                <div class="text-md-end text-center mt-2 mt-md-0">
                    <small class="text-muted">
                        <i class="fas fa-heart text-danger me-1"></i>
                        Designed for your academic success
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Study Motivation Quote (Random) -->
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-12">
                <div class="text-center py-2 bg-primary bg-opacity-10 rounded">
                    <small class="text-primary fw-medium fst-italic">
                        <i class="fas fa-quote-left me-1"></i>
                        "Kesuksesan adalah hasil dari persiapan, kerja keras, dan belajar dari kegagalan."
                        <i class="fas fa-quote-right ms-1"></i>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Back to Top Button -->
    <button type="button" 
            class="btn btn-primary rounded-circle position-fixed shadow-lg border-0" 
            id="backToTop" 
            style="bottom: 30px; right: 30px; width: 55px; height: 55px; display: none; z-index: 1000; transition: all 0.3s ease;"
            title="Kembali ke Atas">
        <i class="fas fa-chevron-up fs-5"></i>
    </button>

    <!-- Footer Styles -->
    <style>
        /* Footer Specific Styles */
        footer {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            width: 100%;
        }
        
        /* Back to Top Button Enhancements */
        #backToTop {
            backdrop-filter: blur(10px);
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
        }
        
        #backToTop:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3) !important;
        }
        
        /* Footer Links Hover Effects */
        footer .btn-outline-primary:hover,
        footer .btn-outline-success:hover,
        footer .btn-outline-info:hover,
        footer .btn-outline-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        /* Progress Cards */
        footer .bg-light {
            border-left: 4px solid var(--success-color);
        }
        
        footer .bg-white {
            border-left: 4px solid var(--primary-color);
        }
        
        /* Motivational Quote Animation */
        footer .bg-primary.bg-opacity-10 {
            animation: gentle-pulse 3s ease-in-out infinite;
        }
        
        @keyframes gentle-pulse {
            0%, 100% { opacity: 0.9; }
            50% { opacity: 1; }
        }
        
        /* Progress bar animation */
        .progress-bar {
            transition: width 0.6s ease;
        }
        
        /* Responsive Footer */
        @media (max-width: 768px) {
            footer {
                text-align: center !important;
            }
            
            footer .row > div {
                margin-bottom: 1rem;
            }
            
            #backToTop {
                bottom: 20px;
                right: 20px;
                width: 50px;
                height: 50px;
            }
            
            footer .col-md-3 {
                margin-bottom: 0.5rem;
            }
            
            footer .d-flex.flex-wrap {
                justify-content: center !important;
            }
        }
    </style>

    <script>
        // Enhanced Back to Top functionality
        $(document).ready(function() {
            const $backToTop = $('#backToTop');
            let isVisible = false;
            
            // Throttled scroll handler for better performance
            let scrollTimeout;
            $(window).on('scroll', function() {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(function() {
                    const scrollTop = $(window).scrollTop();
                    const shouldShow = scrollTop > 300;
                    
                    if (shouldShow && !isVisible) {
                        $backToTop.fadeIn(300);
                        isVisible = true;
                    } else if (!shouldShow && isVisible) {
                        $backToTop.fadeOut(300);
                        isVisible = false;
                    }
                }, 50);
            });

            // Enhanced smooth scroll to top with easing
            $backToTop.on('click', function(e) {
                e.preventDefault();
                
                // Add click animation
                $(this).addClass('pulse');
                
                // Smooth scroll with custom easing
                $('html, body').animate(
                    { scrollTop: 0 }, 
                    {
                        duration: 800,
                        easing: 'easeInOutCubic',
                        complete: function() {
                            $backToTop.removeClass('pulse');
                        }
                    }
                );
                
                return false;
            });
            
            // Add hover effects for footer buttons
            $('footer .btn').hover(
                function() {
                    $(this).addClass('shadow-sm');
                },
                function() {
                    $(this).removeClass('shadow-sm');
                }
            );
            
            // Animate progress bars on page load
            $('.progress-bar').each(function() {
                const width = $(this).css('width');
                $(this).css('width', '0').delay(500).animate({width: width}, 1500);
            });
            
            // Random motivational quotes
            const quotes = [
                "Kesuksesan adalah hasil dari persiapan, kerja keras, dan belajar dari kegagalan.",
                "Belajar tanpa berpikir itu sia-sia, berpikir tanpa belajar itu berbahaya.",
                "Pendidikan adalah investasi terbaik untuk masa depan.",
                "Ilmu pengetahuan adalah kekuatan yang sesungguhnya.",
                "Jangan pernah berhenti belajar, karena hidup tidak pernah berhenti mengajar."
            ];
            
            // Change quote every 10 seconds
            let currentQuote = 0;
            setInterval(function() {
                currentQuote = (currentQuote + 1) % quotes.length;
                $('.fst-italic').fadeOut(500, function() {
                    $(this).html(`
                        <i class="fas fa-quote-left me-1"></i>
                        "${quotes[currentQuote]}"
                        <i class="fas fa-quote-right ms-1"></i>
                    `).fadeIn(500);
                });
            }, 10000);
        });

        // Custom easing function
        $.easing.easeInOutCubic = function (x, t, b, c, d) {
            if ((t/=d/2) < 1) return c/2*t*t*t + b;
            return c/2*((t-=2)*t*t + 2) + b;
        };
    </script>
</footer>