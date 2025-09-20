/**
 * Admin Dashboard JavaScript
 * LMS Trimurti Husada
 */

$(document).ready(function() {
    // Initialize sidebar
    initializeSidebar();
    
    // Initialize data tables
    initializeDataTables();
    
    // Initialize tooltips
    initializeTooltips();
    
    // Initialize alerts auto-hide
    initializeAlerts();
    
    // Initialize bulk actions
    initializeBulkActions();
});

/**
 * Initialize sidebar functionality - Modern Design
 */
function initializeSidebar() {
    const sidebar = $('.sidebar-wrapper');
    const content = $('#content');
    const toggleBtn = $('#sidebarToggle, .sidebar-toggle');
    
    // Toggle sidebar
    toggleBtn.on('click', function(e) {
        e.preventDefault();
        toggleSidebar();
    });
    
    // Restore sidebar state on load
    const isCollapsed = localStorage.getItem('admin-sidebar-collapsed') === 'true';
    if (isCollapsed) {
        sidebar.addClass('collapsed');
        content.addClass('sidebar-collapsed');
        updateToggleIcon(true);
    }
    
    // Handle responsive behavior
    handleResponsiveSidebar();
    
    // Initialize sidebar animations
    initializeSidebarAnimations();
    
    // Initialize quick actions
    initializeQuickActions();
}

/**
 * Toggle sidebar collapse state
 */
function toggleSidebar() {
    const sidebar = $('.sidebar-wrapper');
    const content = $('#content');
    const isCollapsed = sidebar.hasClass('collapsed');
    
    if (isCollapsed) {
        sidebar.removeClass('collapsed');
        content.removeClass('sidebar-collapsed');
        localStorage.setItem('admin-sidebar-collapsed', 'false');
        updateToggleIcon(false);
    } else {
        sidebar.addClass('collapsed');
        content.addClass('sidebar-collapsed');
        localStorage.setItem('admin-sidebar-collapsed', 'true');
        updateToggleIcon(true);
    }
    
    // Trigger resize event for charts and other components
    setTimeout(() => {
        window.dispatchEvent(new Event('resize'));
    }, 300);
}

/**
 * Update toggle button icon
 */
function updateToggleIcon(collapsed) {
    const icon = $('#sidebarToggle i, .sidebar-toggle i');
    if (collapsed) {
        icon.removeClass('fa-bars').addClass('fa-arrow-right');
    } else {
        icon.removeClass('fa-arrow-right').addClass('fa-bars');
    }
}

/**
 * Handle responsive sidebar behavior
 */
function handleResponsiveSidebar() {
    const sidebar = $('.sidebar-wrapper');
    const overlay = $('<div class="sidebar-overlay"></div>');
    
    // Add overlay for mobile
    if (!$('.sidebar-overlay').length) {
        $('body').append(overlay);
    }
    
    // Mobile toggle
    if (window.innerWidth <= 768) {
        sidebar.addClass('mobile-hidden');
        
        // Mobile toggle behavior
        $('#sidebarToggle, .sidebar-toggle').off('click.mobile').on('click.mobile', function(e) {
            e.preventDefault();
            sidebar.toggleClass('mobile-hidden');
            $('.sidebar-overlay').toggleClass('active');
            $('body').toggleClass('sidebar-open');
        });
        
        // Close on overlay click
        $('.sidebar-overlay').on('click', function() {
            sidebar.addClass('mobile-hidden');
            $(this).removeClass('active');
            $('body').removeClass('sidebar-open');
        });
    } else {
        sidebar.removeClass('mobile-hidden');
        $('.sidebar-overlay').removeClass('active');
        $('body').removeClass('sidebar-open');
    }
}

/**
 * Initialize sidebar animations and effects
 */
function initializeSidebarAnimations() {
    // Menu item hover effects
    $('.menu-link').on('mouseenter', function() {
        $(this).addClass('hovered');
    }).on('mouseleave', function() {
        $(this).removeClass('hovered');
    });
    
    // Smooth scrolling for sidebar menu
    $('.sidebar-menu').on('scroll', function() {
        const scrollTop = $(this).scrollTop();
        if (scrollTop > 50) {
            $('.sidebar-header').addClass('scrolled');
        } else {
            $('.sidebar-header').removeClass('scrolled');
        }
    });
}

/**
 * Initialize quick actions in sidebar footer
 */
function initializeQuickActions() {
    // Add global functions for quick actions
    window.showNotifications = function() {
        // Implementation for notifications
        showAlert('info', 'Fitur notifikasi akan segera tersedia!', 3000);
    };
    
    window.showHelp = function() {
        // Implementation for help
        showAlert('info', 'Bantuan: Gunakan sidebar untuk navigasi. Klik tombol toggle untuk menyembunyikan/menampilkan menu.', 5000);
    };
    
    window.confirmLogout = function() {
        if (confirm('Apakah Anda yakin ingin logout dari sistem?')) {
            // Show loading
            const logoutBtn = $('.logout-btn');
            logoutBtn.html('<i class="fas fa-spinner fa-spin"></i>');
            logoutBtn.prop('disabled', true);
            
            // Submit logout form
            document.getElementById('logout-form').submit();
        }
    };
}

/**
 * Initialize DataTables
 */
function initializeDataTables() {
    if ($.fn.DataTable) {
        $('.data-table').each(function() {
            const table = $(this);
            
            table.DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    },
                    emptyTable: "Tidak ada data yang tersedia",
                    zeroRecords: "Tidak ditemukan data yang sesuai"
                },
                dom: '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-5"i><"col-sm-7"p>>',
                drawCallback: function() {
                    // Reinitialize tooltips for new elements
                    $('[data-bs-toggle="tooltip"]').tooltip();
                }
            });
        });
    }
}

/**
 * Initialize tooltips
 */
function initializeTooltips() {
    if (typeof bootstrap !== 'undefined') {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }
}

/**
 * Initialize alerts auto-hide
 */
function initializeAlerts() {
    // Auto hide success alerts after 5 seconds
    $('.alert-success').delay(5000).fadeOut();
    
    // Auto hide info alerts after 4 seconds
    $('.alert-info').delay(4000).fadeOut();
    
    // Alert close button
    $('.alert .btn-close').on('click', function() {
        $(this).closest('.alert').fadeOut();
    });
}

/**
 * Initialize bulk actions
 */
function initializeBulkActions() {
    // Bulk select all
    $('.bulk-select-all').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.bulk-select-item').prop('checked', isChecked);
        toggleBulkActions();
    });
    
    // Individual select
    $('.bulk-select-item').on('change', function() {
        const totalItems = $('.bulk-select-item').length;
        const checkedItems = $('.bulk-select-item:checked').length;
        
        $('.bulk-select-all').prop('checked', totalItems === checkedItems);
        toggleBulkActions();
    });
    
    // Bulk action form submit
    $('.bulk-action-form').on('submit', function(e) {
        const checkedItems = $('.bulk-select-item:checked');
        
        if (checkedItems.length === 0) {
            e.preventDefault();
            showAlert('warning', 'Pilih minimal satu item untuk melakukan aksi bulk.');
            return false;
        }
        
        const action = $('.bulk-action-select').val();
        if (!action) {
            e.preventDefault();
            showAlert('warning', 'Pilih aksi yang akan dilakukan.');
            return false;
        }
        
        // Confirm action
        const confirmMessage = getConfirmMessage(action, checkedItems.length);
        if (!confirm(confirmMessage)) {
            e.preventDefault();
            return false;
        }
    });
}

/**
 * Toggle bulk actions visibility
 */
function toggleBulkActions() {
    const checkedItems = $('.bulk-select-item:checked').length;
    const bulkActions = $('.bulk-actions');
    
    if (checkedItems > 0) {
        bulkActions.addClass('show');
        $('.bulk-count').text(checkedItems);
    } else {
        bulkActions.removeClass('show');
    }
}

/**
 * Get confirmation message for bulk actions
 */
function getConfirmMessage(action, count) {
    const messages = {
        'delete': `Apakah Anda yakin ingin menghapus ${count} item yang dipilih? Tindakan ini tidak dapat dibatalkan.`,
        'activate': `Apakah Anda yakin ingin mengaktifkan ${count} item yang dipilih?`,
        'deactivate': `Apakah Anda yakin ingin menonaktifkan ${count} item yang dipilih?`,
        'archive': `Apakah Anda yakin ingin mengarsipkan ${count} item yang dipilih?`
    };
    
    return messages[action] || `Apakah Anda yakin ingin melakukan aksi ini pada ${count} item yang dipilih?`;
}

/**
 * Show alert message
 */
function showAlert(type, message, duration = 5000) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Remove existing alerts
    $('.alert').remove();
    
    // Add new alert
    $('.container-fluid').prepend(alertHtml);
    
    // Auto hide
    if (duration > 0) {
        setTimeout(() => {
            $('.alert').fadeOut();
        }, duration);
    }
}

/**
 * Confirm delete action
 */
function confirmDelete(message = 'Apakah Anda yakin ingin menghapus item ini?') {
    return confirm(message);
}

/**
 * Format file size
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

/**
 * Loading state management
 */
function setLoadingState(element, loading = true) {
    const $element = $(element);
    
    if (loading) {
        $element.prop('disabled', true);
        const originalText = $element.text();
        $element.data('original-text', originalText);
        $element.html('<i class="fas fa-spinner fa-spin"></i> Loading...');
    } else {
        $element.prop('disabled', false);
        const originalText = $element.data('original-text');
        $element.html(originalText);
    }
}

/**
 * AJAX helper with CSRF token
 */
function ajaxRequest(url, data = {}, method = 'POST') {
    return $.ajax({
        url: url,
        method: method,
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

/**
 * Copy text to clipboard
 */
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            showAlert('success', 'Teks berhasil disalin ke clipboard!', 2000);
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showAlert('success', 'Teks berhasil disalin ke clipboard!', 2000);
    }
}

/**
 * Window resize handler
 */
$(window).on('resize', function() {
    // Handle mobile sidebar
    if (window.innerWidth <= 768) {
        if (!$('#sidebar').hasClass('mobile-hidden')) {
            $('#sidebar').addClass('active');
            $('#content').removeClass('active');
        }
    } else {
        // Restore sidebar state on desktop
        const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
        if (isCollapsed) {
            $('#sidebar').addClass('active');
            $('#content').addClass('active');
        } else {
            $('#sidebar').removeClass('active');
            $('#content').removeClass('active');
        }
    }
});

/**
 * Global error handler
 */
$(document).ajaxError(function(event, xhr, settings, error) {
    if (xhr.status === 419) {
        showAlert('danger', 'Sesi telah berakhir. Silakan refresh halaman.', 0);
    } else if (xhr.status === 403) {
        showAlert('danger', 'Anda tidak memiliki izin untuk melakukan aksi ini.', 0);
    } else if (xhr.status >= 500) {
        showAlert('danger', 'Terjadi kesalahan server. Silakan coba lagi.', 0);
    }
});