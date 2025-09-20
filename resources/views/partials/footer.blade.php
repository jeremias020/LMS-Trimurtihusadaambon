<!-- Footer Sama Seperti Admin/Guru -->
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
    // Enhanced Back to Top functionality (sama seperti footer guru/admin)
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
            $(this).addClass('animate__animated animate__pulse');
            
            // Smooth scroll with custom easing
            $('html, body').animate(
                { scrollTop: 0 }, 
                {
                    duration: 800,
                    easing: 'easeInOutCubic',
                    complete: function() {
                        $backToTop.removeClass('animate__animated animate__pulse');
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
</script>
