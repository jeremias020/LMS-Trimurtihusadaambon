<footer class="mt-auto bg-gradient-to-r from-gray-50 to-gray-100 border-top border-primary border-opacity-20 py-4">
    <div class="container-fluid">
        <!-- Main Footer Content -->
        <div class="row align-items-center mb-3">
            <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-circle p-2 me-3">
                        <i class="fas fa-graduation-cap text-white"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">LMS Trimurti Husada</div>
                        <small class="text-muted">Learning Management System</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                <div class="text-center">
                    <div class="d-flex justify-content-center gap-3">
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
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="text-lg-end text-center">
                    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-lg-end gap-2">
                        <!-- System Status -->
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-pill px-2 py-1 me-2">
                                <i class="fas fa-wifi text-success me-1"></i>
                                <small class="text-success fw-medium">Online</small>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-code me-1"></i>v1.0.0
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics Row -->
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
        footer .btn-outline-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        /* Statistics Cards */
        footer .bg-light {
            border-left: 4px solid var(--primary-color);
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
        }
    </style>

    <script>
        // Enhanced Back to Top functionality and stats loading
        $(document).ready(function() {
            const $backToTop = $('#backToTop');
            let isVisible = false;
            
            // Load dynamic statistics
            loadSystemStats();
            
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
        });

        // Custom easing function
        $.easing.easeInOutCubic = function (x, t, b, c, d) {
            if ((t/=d/2) < 1) return c/2*t*t*t + b;
            return c/2*((t-=2)*t*t + 2) + b;
        };
        
        // Function to load system statistics
        function loadSystemStats() {
            // Simulate loading stats - replace with actual API call
            setTimeout(function() {
                // Mock data - replace with actual data from your API
                const mockStats = {
                    total_users: Math.floor(Math.random() * 500) + 100,
                    active_sessions: Math.floor(Math.random() * 50) + 5,
                    storage_used: Math.floor(Math.random() * 40) + 20 + '%',
                    system_load: 'Normal'
                };
                
                // Update stats with animation
                $('#stat-total-users').fadeOut(200, function() {
                    $(this).text(mockStats.total_users).fadeIn(200);
                });
                $('#stat-active-sessions').fadeOut(200, function() {
                    $(this).text(mockStats.active_sessions).fadeIn(200);
                });
                $('#stat-storage-used').fadeOut(200, function() {
                    $(this).text(mockStats.storage_used).fadeIn(200);
                });
                $('#stat-system-load').fadeOut(200, function() {
                    $(this).text(mockStats.system_load).fadeIn(200);
                });
            }, 1000);
            
            // Uncomment and modify this for real API integration:
            /*
            $.ajax({
                url: '/api/admin/stats',
                method: 'GET',
                success: function(data) {
                    $('#stat-total-users').text(data.total_users || 0);
                    $('#stat-active-sessions').text(data.active_sessions || 0);
                    $('#stat-storage-used').text(data.storage_used || '0%');
                    $('#stat-system-load').text(data.system_load || 'Normal');
                },
                error: function() {
                    console.log('Failed to load system statistics');
                }
            });
            */
        }
    </script>
</footer>