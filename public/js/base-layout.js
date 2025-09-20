/**
 * Base Layout JavaScript untuk LMS Trimurti Husada
 * Fungsi-fungsi umum yang digunakan di semua role (Admin, Guru, Siswa)
 */

(function($) {
    'use strict';

    // Fungsi utama yang dijalankan saat DOM ready
    $(document).ready(function() {
        initializeSidebar();
        initializeAlerts();
        initializeTooltips();
        initializeModals();
        initializeSearch();
        initializeScrollToTop();
        initializeFormValidation();
    });

    /**
     * Inisialisasi sidebar functionality
     */
    function initializeSidebar() {
        // Sidebar toggle untuk semua versi tombol
        $('#sidebarToggle, .sidebar-toggle, #sidebarCollapse, #mobileSidebarToggle').on('click', function(e) {
            e.preventDefault();
            
            const $sidebar = $('.sidebar');
            const $mainContent = $('.main-content');
            
            // Toggle collapsed class
            $sidebar.toggleClass('collapsed');
            $mainContent.toggleClass('expanded');
            
            // Animate toggle button
            $(this).find('i').addClass('fa-spin');
            setTimeout(() => {
                $(this).find('i').removeClass('fa-spin');
            }, 300);
            
            // Save state in localStorage
            const isCollapsed = $sidebar.hasClass('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
            
            // Trigger custom event
            $(document).trigger('sidebar:toggled', { collapsed: isCollapsed });
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
                if (!$(e.target).closest('.sidebar, #mobileSidebarToggle, .sidebar-toggle').length) {
                    $('.sidebar').removeClass('show');
                }
            }
        });

        // Sidebar menu item click handling
        $('.sidebar .nav-link').on('click', function() {
            // Remove active class from all nav links
            $('.sidebar .nav-link').removeClass('active');
            // Add active class to clicked item
            $(this).addClass('active');
        });
    }

    /**
     * Inisialisasi alert functionality
     */
    function initializeAlerts() {
        // Auto-hide alerts after 5 seconds
        $('.alert:not(.alert-permanent)').each(function() {
            const $alert = $(this);
            setTimeout(() => {
                $alert.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
        });

        // Alert close button
        $('.alert .btn-close, .alert .close').on('click', function() {
            $(this).closest('.alert').fadeOut(300, function() {
                $(this).remove();
            });
        });
    }

    /**
     * Inisialisasi tooltips
     */
    function initializeTooltips() {
        // Initialize Bootstrap tooltips
        if (typeof bootstrap !== 'undefined') {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [title]:not([title=""])'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    delay: { show: 500, hide: 100 }
                });
            });
        }
    }

    /**
     * Inisialisasi modal functionality
     */
    function initializeModals() {
        // Auto focus pada input pertama saat modal dibuka
        $('.modal').on('shown.bs.modal', function () {
            $(this).find('input, textarea, select').first().focus();
        });

        // Konfirmasi delete
        $('[data-confirm-delete]').on('click', function(e) {
            e.preventDefault();
            const $element = $(this);
            const message = $element.data('confirm-delete') || 'Apakah Anda yakin ingin menghapus data ini?';
            
            if (confirm(message)) {
                const form = $element.data('form');
                if (form) {
                    $(form).submit();
                } else {
                    window.location.href = $element.attr('href');
                }
            }
        });
    }

    /**
     * Inisialisasi search functionality
     */
    function initializeSearch() {
        // Global search dengan debounce
        let searchTimeout;
        $('#globalSearch, .global-search').on('input', function() {
            const $input = $(this);
            const query = $input.val();
            
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (query.length >= 3) {
                    performSearch(query, $input);
                } else if (query.length === 0) {
                    clearSearchResults($input);
                }
            }, 500);
        });

        // Search suggestions click handler
        $(document).on('click', '.search-suggestion', function() {
            const query = $(this).data('search') || $(this).text();
            const $searchInput = $('#globalSearch, .global-search').first();
            $searchInput.val(query);
            $searchInput.closest('form').submit();
        });

        // Clear search
        $('.search-clear').on('click', function() {
            const $input = $(this).siblings('input');
            $input.val('').focus();
            clearSearchResults($input);
        });
    }

    /**
     * Perform search operation
     */
    function performSearch(query, $input) {
        // Implementasi search bisa disesuaikan per halaman
        const $suggestions = $input.siblings('.search-suggestions');
        if ($suggestions.length > 0) {
            $suggestions.removeClass('d-none').addClass('d-block');
        }
        
        // Trigger custom event untuk handling search di halaman spesifik
        $(document).trigger('search:performed', { query: query, input: $input });
    }

    /**
     * Clear search results
     */
    function clearSearchResults($input) {
        const $suggestions = $input.siblings('.search-suggestions');
        if ($suggestions.length > 0) {
            $suggestions.addClass('d-none').removeClass('d-block');
        }
        
        // Trigger custom event
        $(document).trigger('search:cleared', { input: $input });
    }

    /**
     * Inisialisasi scroll to top functionality
     */
    function initializeScrollToTop() {
        const $backToTop = $('#backToTop, .back-to-top');
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

        // Enhanced smooth scroll to top
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
    }

    /**
     * Inisialisasi form validation
     */
    function initializeFormValidation() {
        // Bootstrap form validation
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    // Focus pada field pertama yang error
                    const firstInvalid = form.querySelector(':invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                    }
                }
                form.classList.add('was-validated');
            });
        });

        // Real-time validation feedback
        $('input[required], textarea[required], select[required]').on('blur', function() {
            const $field = $(this);
            if ($field.val().trim() === '') {
                $field.addClass('is-invalid').removeClass('is-valid');
            } else {
                $field.addClass('is-valid').removeClass('is-invalid');
            }
        });
    }

    /**
     * Utility Functions
     */
    window.LMSUtils = {
        // Show loading state
        showLoading: function(element, text = 'Loading...') {
            const $element = $(element);
            $element.data('original-html', $element.html());
            $element.html(`<i class="fas fa-spinner fa-spin me-2"></i>${text}`);
            $element.prop('disabled', true);
        },

        // Hide loading state
        hideLoading: function(element) {
            const $element = $(element);
            const originalHtml = $element.data('original-html');
            if (originalHtml) {
                $element.html(originalHtml);
            }
            $element.prop('disabled', false);
        },

        // Show toast notification
        showToast: function(message, type = 'info', duration = 3000) {
            const toastHtml = `
                <div class="toast align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-info-circle me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            
            let $toastContainer = $('.toast-container');
            if ($toastContainer.length === 0) {
                $toastContainer = $('<div class="toast-container position-fixed top-0 end-0 p-3"></div>');
                $('body').append($toastContainer);
            }
            
            const $toast = $(toastHtml);
            $toastContainer.append($toast);
            
            const toast = new bootstrap.Toast($toast[0], { delay: duration });
            toast.show();
            
            // Remove from DOM after hidden
            $toast.on('hidden.bs.toast', function() {
                $(this).remove();
            });
        },

        // Format number with thousand separator
        formatNumber: function(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },

        // Format date to Indonesian format
        formatDate: function(date, includeTime = false) {
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            
            if (includeTime) {
                options.hour = '2-digit';
                options.minute = '2-digit';
            }
            
            return new Intl.DateTimeFormat('id-ID', options).format(new Date(date));
        },

        // Validate email format
        isValidEmail: function(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },

        // Debounce function
        debounce: function(func, wait, immediate) {
            let timeout;
            return function executedFunction() {
                const context = this;
                const args = arguments;
                const later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        }
    };

    // Custom easing function
    $.easing.easeInOutCubic = function (x, t, b, c, d) {
        if ((t/=d/2) < 1) return c/2*t*t*t + b;
        return c/2*((t-=2)*t*t + 2) + b;
    };

    // Expose global functions for backward compatibility
    window.initializeSidebar = initializeSidebar;
    window.showLoading = LMSUtils.showLoading;
    window.hideLoading = LMSUtils.hideLoading;
    window.showToast = LMSUtils.showToast;

})(jQuery);