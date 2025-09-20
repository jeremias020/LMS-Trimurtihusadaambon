// Komponen Chart untuk LMS SMK Kesehatan Trimurti Husada Ambon

class LMSChart {
    constructor(elementId, options = {}) {
        this.elementId = elementId;
        this.options = options;
        this.chart = null;
        this.schoolColors = {
            primary: '#2c7da0',
            secondary: '#a9d6e5',
            accent: '#e63946',
            success: '#40916c',
            warning: '#ff9e00',
            light: '#f8f9fa'
        };
        this.init();
    }

    init() {
        const ctx = document.getElementById(this.elementId);
        if (!ctx) {
            console.error(`Element dengan ID ${this.elementId} tidak ditemukan.`);
            return;
        }

        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        color: '#4e4e4e',
                        font: {
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                            size: 13
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: {
                        size: 14,
                        family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13,
                        family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                    },
                    padding: 12,
                    cornerRadius: 6,
                    displayColors: true,
                    boxWidth: 10,
                    boxHeight: 10
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                        }
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            }
        };

        const config = {
            type: this.options.type || 'bar',
            data: this.options.data || {},
            options: { ...defaultOptions, ...this.options.options }
        };

        // Terapkan warna sekolah jika tidak ada warna yang ditentukan
        if (config.data.datasets) {
            config.data.datasets.forEach((dataset, index) => {
                if (!dataset.backgroundColor) {
                    const colorKeys = Object.keys(this.schoolColors);
                    dataset.backgroundColor = this.schoolColors[colorKeys[index % colorKeys.length]];
                }

                if (!dataset.borderColor && (config.type === 'line' || config.type === 'radar')) {
                    const colorKeys = Object.keys(this.schoolColors);
                    dataset.borderColor = this.schoolColors[colorKeys[index % colorKeys.length]];
                }

                if (!dataset.pointBackgroundColor && config.type === 'line') {
                    dataset.pointBackgroundColor = this.schoolColors.primary;
                }
            });
        }

        this.chart = new Chart(ctx, config);

        // Handle resize event untuk memastikan chart tetap responsif
        window.addEventListener('resize', () => {
            if (this.chart) {
                this.chart.resize();
            }
        });
    }

    update(data) {
        if (this.chart && data) {
            // Update datasets jika ada
            if (data.datasets) {
                this.chart.data.datasets = data.datasets;
            }

            // Update labels jika ada
            if (data.labels) {
                this.chart.data.labels = data.labels;
            }

            this.chart.update();
        }
    }

    changeType(type) {
        if (this.chart) {
            this.chart.destroy();
            this.options.type = type;
            this.init();
        }
    }

    changeColors(colors) {
        if (this.chart && this.chart.data.datasets) {
            this.chart.data.datasets.forEach((dataset, index) => {
                const colorKeys = Object.keys(colors);
                dataset.backgroundColor = colors[colorKeys[index % colorKeys.length]];

                if (dataset.borderColor) {
                    dataset.borderColor = colors[colorKeys[index % colorKeys.length]];
                }
            });

            this.chart.update();
        }
    }

    destroy() {
        if (this.chart) {
            this.chart.destroy();
            this.chart = null;
        }
    }

    // Method untuk membuat chart progress
    static createProgressChart(elementId, value, max = 100, options = {}) {
        const ctx = document.getElementById(elementId);
        if (!ctx) {
            console.error(`Element dengan ID ${elementId} tidak ditemukan.`);
            return null;
        }

        const schoolColors = {
            primary: '#2c7da0',
            light: '#e9ecef'
        };

        const percentage = (value / max) * 100;
        const config = {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [percentage, 100 - percentage],
                    backgroundColor: [
                        options.color || schoolColors.primary,
                        schoolColors.light
                    ],
                    borderWidth: 0,
                    cutout: '80%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: false
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        };

        // Tambahkan text di tengah chart
        const centerTextPlugin = {
            id: 'centerText',
            beforeDraw: function(chart) {
                if (chart.config.type === 'doughnut') {
                    const ctx = chart.ctx;
                    const chartArea = chart.chartArea;
                    const centerX = (chartArea.left + chartArea.right) / 2;
                    const centerY = (chartArea.top + chartArea.bottom) / 2;

                    ctx.save();
                    ctx.font = 'bold 24px Segoe UI';
                    ctx.fillStyle = options.color || schoolColors.primary;
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(`${Math.round(percentage)}%`, centerX, centerY);

                    ctx.font = '13px Segoe UI';
                    ctx.fillStyle = '#6c757d';
                    ctx.fillText('Tercapai', centerX, centerY + 25);
                    ctx.restore();
                }
            }
        };

        // Daftarkan plugin jika belum ada
        if (!Chart.registry.getPlugin('centerText')) {
            Chart.register(centerTextPlugin);
        }

        return new Chart(ctx, config);
    }

    // Method untuk membuat chart sparkline
    static createSparklineChart(elementId, data, options = {}) {
        const ctx = document.getElementById(elementId);
        if (!ctx) {
            console.error(`Element dengan ID ${elementId} tidak ditemukan.`);
            return null;
        }

        const schoolColors = {
            primary: '#2c7da0'
        };

        const config = {
            type: 'line',
            data: {
                labels: data.labels || Array.from({length: data.values.length}, (_, i) => i + 1),
                datasets: [{
                    data: data.values,
                    borderColor: options.color || schoolColors.primary,
                    borderWidth: 3,
                    fill: true,
                    backgroundColor: 'rgba(42, 157, 143, 0.1)',
                    pointRadius: 4,
                    pointBackgroundColor: schoolColors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: false
                    },
                    y: {
                        display: false
                    }
                }
            }
        };

        return new Chart(ctx, config);
    }

    // Method untuk membuat radar chart
    static createRadarChart(elementId, data, options = {}) {
        const ctx = document.getElementById(elementId);
        if (!ctx) {
            console.error(`Element dengan ID ${elementId} tidak ditemukan.`);
            return null;
        }

        const schoolColors = {
            primary: 'rgba(42, 157, 143, 0.6)',
            secondary: 'rgba(233, 196, 106, 0.6)',
            accent: 'rgba(231, 111, 81, 0.6)'
        };

        const config = {
            type: 'radar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: data.label || 'Pemahaman',
                    data: data.values,
                    backgroundColor: options.backgroundColor || schoolColors.primary,
                    borderColor: options.borderColor || '#2a9d8f',
                    pointBackgroundColor: '#2a9d8f',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#2a9d8f'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    r: {
                        angleLines: {
                            display: true
                        },
                        suggestedMin: 0,
                        suggestedMax: 100
                    }
                }
            }
        };

        return new Chart(ctx, config);
    }
}

// Export untuk penggunaan global
if (typeof window !== 'undefined') {
    window.LMSChart = LMSChart;
}

// Export untuk modul ES6
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LMSChart;
}
