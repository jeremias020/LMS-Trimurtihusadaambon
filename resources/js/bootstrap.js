// Bootstrap application dependencies
// Import required modules
import axios from 'axios';
import * as bootstrap from 'bootstrap';
import Popper from '@popperjs/core';

// Make dependencies available globally
window.axios = axios;
window.bootstrap = bootstrap;
window.Popper = Popper;

// Configure axios defaults
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Setup CSRF token for axios
const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
} else {
    console.warn('CSRF token meta tag not found');
}

// Setup axios interceptors for error handling
window.axios.interceptors.response.use(
    response => response,
    error => {
        // Handle 401 Unauthorized
        if (error.response && error.response.status === 401) {
            console.warn('Session expired, redirecting to login...');
            // You can redirect to login page or show a modal
            if (window.LMSApp) {
                window.LMSApp.showNotification('Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            }
        }

        // Handle 403 Forbidden
        if (error.response && error.response.status === 403) {
            if (window.LMSApp) {
                window.LMSApp.showNotification('Anda tidak memiliki izin untuk mengakses halaman ini.', 'danger');
            }
        }

        // Handle 404 Not Found
        if (error.response && error.response.status === 404) {
            if (window.LMSApp) {
                window.LMSApp.showNotification('Data tidak ditemukan.', 'warning');
            }
        }

        // Handle 422 Validation Error
        if (error.response && error.response.status === 422) {
            if (window.LMSApp) {
                window.LMSApp.showValidationErrors(error.response.data.errors);
            }
        }

        // Handle 500 Server Error
        if (error.response && error.response.status === 500) {
            if (window.LMSApp) {
                window.LMSApp.showNotification('Terjadi kesalahan server. Silakan coba lagi.', 'danger');
            }
        }

        return Promise.reject(error);
    }
);

// Initialize Bootstrap components when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all tooltips
    initializeTooltips();

    // Initialize all popovers
    initializePopovers();

    // Initialize all modals
    initializeModals();

    // Initialize all dropdowns
    initializeDropdowns();

    // Initialize all alerts
    initializeAlerts();

    // Setup plugin v0.7.8 compatibility
    setupPluginCompatibility();
});

// Function to initialize tooltips
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Function to initialize popovers
function initializePopovers() {
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.forEach(function (popoverTriggerEl) {
        new bootstrap.Popover(popoverTriggerEl);
    });
}

// Function to initialize modals
function initializeModals() {
    const modalList = [].slice.call(document.querySelectorAll('[data-bs-toggle="modal"]'));
    modalList.forEach(function (modalTriggerEl) {
        modalTriggerEl.addEventListener('click', function() {
            const modalTarget = this.getAttribute('data-bs-target');
            const modalElement = document.querySelector(modalTarget);
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        });
    });

    // Auto-focus on first input when modal is shown
    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.addEventListener('shown.bs.modal', function() {
            const firstInput = this.querySelector('input:not([type="hidden"]):not([disabled]), textarea:not([disabled]), select:not([disabled])');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        });

        // Reset form when modal is hidden
        modal.addEventListener('hidden.bs.modal', function() {
            const form = this.querySelector('form');
            if (form) {
                form.reset();
                form.classList.remove('was-validated');
            }

            // Clear validation errors
            const invalidFeedbacks = this.querySelectorAll('.invalid-feedback');
            invalidFeedbacks.forEach(el => {
                if (!el.hasAttribute('data-bs-original')) {
                    el.remove();
                }
            });

            const invalidInputs = this.querySelectorAll('.is-invalid');
            invalidInputs.forEach(el => el.classList.remove('is-invalid'));
        });
    });
}

// Function to initialize dropdowns
function initializeDropdowns() {
    const dropdownList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    dropdownList.forEach(function (dropdownTriggerEl) {
        new bootstrap.Dropdown(dropdownTriggerEl);
    });
}

// Function to initialize alerts
function initializeAlerts() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'all 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 500);
        }, 5000);
    });

    // Setup alert close buttons
    const closeButtons = document.querySelectorAll('.alert .btn-close');
    closeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const alert = this.closest('.alert');
            if (alert) {
                alert.style.transition = 'all 0.3s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 300);
            }
        });
    });
}

// Function to setup plugin v0.7.8 compatibility
function setupPluginCompatibility() {
    // Check if plugin v0.7.8 is available
    if (typeof Plugin !== 'undefined' && Plugin.version === '0.7.8') {
        console.log('Plugin v0.7.8 detected, setting up compatibility...');

        // Setup plugin event listeners
        document.addEventListener('plugin:ready', function(event) {
            console.log('Plugin is ready:', event.detail);
            initializePluginComponents();
        });

        document.addEventListener('plugin:dataLoaded', function(event) {
            console.log('Plugin data loaded:', event.detail);
            updateUIWithPluginData(event.detail);
        });

        document.addEventListener('plugin:error', function(event) {
            console.error('Plugin error:', event.detail);
            if (window.LMSApp) {
                window.LMSApp.showNotification(event.detail.message || 'Plugin error occurred', 'danger');
            }
        });

        // Trigger plugin initialization
        if (typeof Plugin.initialize === 'function') {
            Plugin.initialize({
                debug: process.env.NODE_ENV === 'development',
                axios: window.axios,
                bootstrap: window.bootstrap
            });
        }
    } else {
        console.warn('Plugin v0.7.8 not found or version mismatch');
    }
}

// Function to initialize plugin components
function initializePluginComponents() {
    if (typeof Plugin !== 'undefined') {
        // Initialize plugin charts
        if (Plugin.charts) {
            Plugin.charts.init({
                Chart: window.Chart
            });
        }

        // Initialize plugin tables
        if (Plugin.tables) {
            Plugin.tables.init({
                bootstrap: window.bootstrap
            });
        }

        // Initialize plugin forms
        if (Plugin.forms) {
            Plugin.forms.init({
                axios: window.axios
            });
        }
    }
}

// Function to update UI with plugin data
function updateUIWithPluginData(data) {
    if (data && typeof data === 'object') {
        // Update charts if data contains chart information
        if (data.charts && Array.isArray(data.charts)) {
            data.charts.forEach(function(chartData) {
                if (chartData.canvasId && chartData.type && chartData.data) {
                    renderPluginChart(chartData);
                }
            });
        }

        // Update tables if data contains table information
        if (data.tables && Array.isArray(data.tables)) {
            data.tables.forEach(function(tableData) {
                if (tableData.tableId && tableData.data) {
                    updatePluginTable(tableData);
                }
            });
        }

        // Show notifications if data contains notifications
        if (data.notifications && Array.isArray(data.notifications)) {
            data.notifications.forEach(function(notification) {
                if (window.LMSApp) {
                    window.LMSApp.showNotification(notification.message, notification.type || 'info');
                }
            });
        }
    }
}

// Function to render plugin chart
function renderPluginChart(chartData) {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js not available');
        return;
    }

    const ctx = document.getElementById(chartData.canvasId);
    if (!ctx) {
        console.error('Chart canvas not found:', chartData.canvasId);
        return;
    }

    try {
        new Chart(ctx, {
            type: chartData.type,
            data: chartData.data,
            options: chartData.options || {}
        });
    } catch (error) {
        console.error('Error rendering chart:', error);
    }
}

// Function to update plugin table
function updatePluginTable(tableData) {
    const table = document.getElementById(tableData.tableId);
    if (!table) {
        console.error('Table not found:', tableData.tableId);
        return;
    }

    try {
        // Clear existing rows (except header)
        const tbody = table.querySelector('tbody');
        if (tbody) {
            tbody.innerHTML = '';

            // Add new rows
            tableData.data.forEach(function(row) {
                const tr = document.createElement('tr');
                Object.values(row).forEach(function(cell) {
                    const td = document.createElement('td');
                    td.textContent = cell;
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });
        }
    } catch (error) {
        console.error('Error updating table:', error);
    }
}

// Export functions for external use
window.bootstrapHelpers = {
    initializeTooltips,
    initializePopovers,
    initializeModals,
    initializeDropdowns,
    initializeAlerts,
    setupPluginCompatibility,
    renderPluginChart,
    updatePluginTable
};
