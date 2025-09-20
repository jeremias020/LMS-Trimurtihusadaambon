// Tema Khusus SMK Kesehatan Trimurti Husada Ambon

const HealthTheme = {
    colors: {
        primary: '#2c7da0',       // Biru medis
        secondary: '#a9d6e5',     // Biru muda
        accent: '#e63946',        // Merah aksen
        success: '#40916c',       // Hijau success
        warning: '#ff9e00',       // Orange warning
        danger: '#dc3545',        // Merah danger
        light: '#f8f9fa'          // Light background
    },

    init: function() {
        this.applyCSSVariables();
        this.applyChartThemes();
    },

    applyCSSVariables: function() {
        const root = document.documentElement;
        Object.entries(this.colors).forEach(([key, value]) => {
            root.style.setProperty(`--health-${key}`, value);
        });
    },

    applyChartThemes: function() {
        if (typeof Chart !== 'undefined') {
            // Default chart configuration untuk tema kesehatan
            Chart.defaults.backgroundColor = this.colors.primary;
            Chart.defaults.borderColor = this.colors.secondary;
            Chart.defaults.color = '#6c757d';
        }
    }
};

// Terapkan tema ketika dokumen siap
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        HealthTheme.init();
    });
} else {
    HealthTheme.init();
}