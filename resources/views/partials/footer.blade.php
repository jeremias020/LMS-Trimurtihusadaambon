<!-- Footer Universal untuk Admin, Guru, dan Siswa -->
<footer class="mt-auto bg-gradient-to-r from-gray-50 to-gray-100 border-top border-primary border-opacity-20 py-4">
    <div class="container-fluid">
        <!-- Main Footer Content -->
        <div class="row align-items-center mb-3">
            <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-circle p-2 me-3">
                        @php $role = Auth::check() ? Auth::user()->role : null; @endphp
                        @if($role === 'student')
                            <i class="fas fa-user-graduate text-white"></i>
                        @else
                            <i class="fas fa-graduation-cap text-white"></i>
                        @endif
                    </div>
                    <div>
                        <div class="fw-bold text-dark">
                            @if($role === 'student') LMS Student Portal
                            @else LMS Trimurti Husada
                            @endif
                        </div>
                        <small class="text-muted">
                            @if($role === 'student') Portal Pembelajaran Siswa
                            @else Learning Management System
                            @endif
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                <div class="text-center">
                    <div class="d-flex justify-content-center gap-3">
                        @if($role === 'student')
                            <a href="{{ route('siswa.materials.index') }}" class="btn btn-outline-primary btn-sm" title="Materi Pembelajaran">
                                <i class="fas fa-book me-1"></i>
                                <span class="d-none d-sm-inline">Materi</span>
                            </a>
                            <a href="{{ route('siswa.assignments.index') }}" class="btn btn-outline-success btn-sm" title="Tugas Saya">
                                <i class="fas fa-tasks me-1"></i>
                                <span class="d-none d-sm-inline">Tugas</span>
                            </a>
                            <a href="{{ route('siswa.reports.index') }}" class="btn btn-outline-info btn-sm" title="Nilai Saya">
                                <i class="fas fa-chart-line me-1"></i>
                                <span class="d-none d-sm-inline">Nilai</span>
                            </a>
                        @else
                            <a href="#" class="btn btn-outline-primary btn-sm" title="Panduan Penggunaan">
                                <i class="fas fa-question-circle me-1"></i>
                                <span class="d-none d-sm-inline">Bantuan</span>
                            </a>
                            <a href="#" class="btn btn-outline-success btn-sm" title="Dokumentasi">
                                <i class="fas fa-book me-1"></i>
                                <span class="d-none d-sm-inline">Docs</span>
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm" title="Support">
                                <i class="fas fa-headset me-1"></i>
                                <span class="d-none d-sm-inline">Support</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="text-lg-end text-center">
                    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-lg-end gap-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-pill px-2 py-1 me-2">
                                @if($role === 'student')
                                    <i class="fas fa-user-check text-success me-1"></i>
                                    <small class="text-success fw-medium">Active</small>
                                @else
                                    <i class="fas fa-wifi text-success me-1"></i>
                                    <small class="text-success fw-medium">Online</small>
                                @endif
                            </div>
                            <small class="text-muted">
                                @if($role === 'student')
                                    <i class="fas fa-graduation-cap me-1"></i>Student v1.0.0
                                @else
                                    <i class="fas fa-code me-1"></i>v1.0.0
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($role === 'student')
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
                @php $completion = isset($progress['completion_rate']) ? $progress['completion_rate'] : 85; @endphp
                <div class="progress mx-auto" style="width: 60px; height: 6px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $completion }}%"></div>
                </div>
                <small class="fw-medium text-success">{{ $progress['completion_rate'] ?? '85' }}%</small>
            </div>
        </div>
        @endif
        @if($role === 'admin')
        <!-- Statistics Row (Admin) -->
        <div class="row align-items-center mb-3 py-2 bg-light rounded">
            <div class="col-md-3 text-center">
                <small class="text-muted d-block">Total Users</small>
                <span class="fw-bold text-primary" id="stat-total-users">-</span>
            </div>
            <div class="col-md-3 text-center">
                <small class="text-muted d-block">Active Sessions</small>
                <span class="fw-bold text-success" id="stat-active-sessions">-</span>
            </div>
            <div class="col-md-3 text-center">
                <small class="text-muted d-block">Storage Used</small>
                <span class="fw-bold text-warning" id="stat-storage-used">-</span>
            </div>
            <div class="col-md-3 text-center">
                <small class="text-muted d-block">System Load</small>
                <span class="fw-bold text-info" id="stat-system-load">Normal</span>
            </div>
        </div>
        @endif
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
                        Built with ❤️ for better education
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
</footer>

<!-- Footer Styles -->
<style>
    /* Footer Gradient Background */
    footer {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        clear: both !important;
        width: 100% !important;
        margin-top: auto;
    }

    /* Back to Top Button Enhancements */
    #backToTop {
        backdrop-filter: blur(10px);
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important;
    }

    #backToTop:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3) !important;
    }

    /* Footer Links Hover Effects */
    footer .btn-outline-primary:hover,
    footer .btn-outline-success:hover,
    footer .btn-outline-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Responsive Footer */
    @media (max-width: 768px) {
        footer {
            text-align: center;
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
    }
</style>

<script>
    // Enhanced Back to Top functionality (no jQuery dependency)
    document.addEventListener('DOMContentLoaded', function () {
        const backToTop = document.getElementById('backToTop');
        if (!backToTop) return;

        let isVisible = false;
        let scrollTimeout;

        function showButton() {
            backToTop.style.display = 'inline-flex';
            backToTop.style.opacity = '1';
            backToTop.style.transform = 'translateY(0)';
            isVisible = true;
        }

        function hideButton() {
            backToTop.style.opacity = '0';
            backToTop.style.transform = 'translateY(6px)';
            window.setTimeout(function () {
                if (!isVisible) backToTop.style.display = 'none';
            }, 200);
            isVisible = false;
        }

        function onScroll() {
            const shouldShow = window.pageYOffset > 300;
            if (shouldShow && !isVisible) {
                showButton();
            } else if (!shouldShow && isVisible) {
                hideButton();
            }
        }

        window.addEventListener('scroll', function () {
            window.clearTimeout(scrollTimeout);
            scrollTimeout = window.setTimeout(onScroll, 50);
        }, { passive: true });

        backToTop.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        document.querySelectorAll('footer .btn').forEach(function (btn) {
            btn.addEventListener('mouseenter', function () {
                btn.classList.add('shadow-sm');
            });
            btn.addEventListener('mouseleave', function () {
                btn.classList.remove('shadow-sm');
            });
        });

        // Admin Statistics Update
        function updateAdminStats() {
            // Update Total Users
            const totalUsersEl = document.getElementById('stat-total-users');
            if (totalUsersEl) {
                // Simulate fetching real data
                totalUsersEl.textContent = Math.floor(Math.random() * 50) + 150;
            }

            // Update Active Sessions
            const activeSessionsEl = document.getElementById('stat-active-sessions');
            if (activeSessionsEl) {
                activeSessionsEl.textContent = Math.floor(Math.random() * 20) + 25;
            }

            // Update Storage Used
            const storageUsedEl = document.getElementById('stat-storage-used');
            if (storageUsedEl) {
                const storageGB = (Math.random() * 5 + 2).toFixed(1);
                storageUsedEl.textContent = storageGB + ' GB';
            }

            // Update System Load
            const systemLoadEl = document.getElementById('stat-system-load');
            if (systemLoadEl) {
                const load = Math.random() * 100;
                let status = 'Normal';
                if (load > 80) status = 'High';
                else if (load > 60) status = 'Medium';
                
                systemLoadEl.textContent = status;
                systemLoadEl.className = 'fw-bold text-' + 
                    (status === 'High' ? 'danger' : status === 'Medium' ? 'warning' : 'info');
            }
        }

        // Initial stats update
        updateAdminStats();

        // Update stats every 30 seconds
        setInterval(updateAdminStats, 30000);

        // Initial state
        backToTop.style.opacity = '0';
        backToTop.style.transform = 'translateY(6px)';
        backToTop.style.display = 'none';
        onScroll();
    });
</script>
