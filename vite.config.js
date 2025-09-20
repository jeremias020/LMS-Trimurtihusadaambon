import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/admin.css',
                'resources/js/admin.js',
                'resources/css/guru.css',
                'resources/js/guru.js',
                'resources/css/siswa.css',
                'resources/js/siswa.js'
            ],
            // Tambahkan konfigurasi untuk hot reload
            refresh: [
                'resources/views/**',
                'app/Http/Livewire/**',
                'app/Filament/**',
                'app/View/Components/**',
            ],
        }),
    ],
    // Konfigurasi resolve alias untuk memudahkan import
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '@css': path.resolve(__dirname, 'resources/css'),
            '@images': path.resolve(__dirname, 'resources/images'),
            '@fonts': path.resolve(__dirname, 'resources/fonts'),
        },
    },
    // Konfigurasi build optimization
    build: {
        // Generate source maps untuk debugging
        sourcemap: true,
        // Minimize CSS
        cssMinify: true,
        // Minimize JS
        minify: 'terser',
        // Konfigurasi terser
        terserOptions: {
            compress: {
                drop_console: process.env.NODE_ENV === 'production',
                drop_debugger: process.env.NODE_ENV === 'production',
            },
        },
        // Konfigurasi chunking
        rollupOptions: {
            output: {
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name]-[hash].js',
                assetFileNames: '[name]-[hash].[ext]',
                manualChunks: {
                    // Pisahkan vendor code
                    vendor: ['chart.js', 'axios', 'bootstrap'],
                },
            },
        },
    },
    // Konfigurasi server dev
    server: {
        host: 'localhost',
        port: 5173,
        // Tambahkan proxy untuk API jika diperlukan
        proxy: {
            '/api': {
                target: 'http://localhost:8000',
                changeOrigin: true,
                rewrite: (path) => path.replace(/^\/api/, ''),
            },
        },
        // Tambahkan CORS headers jika diperlukan
        cors: true,
    },
    // Konfigurasi CSS
    css: {
        // Konfigurasi preprocessor CSS
        preprocessorOptions: {
            scss: {
                additionalData: `@import "@css/variables.scss";`,
            },
            less: {
                additionalData: `@import "@css/variables.less";`,
            },
        },
        // Konfigurasi PostCSS
        postcss: {
            plugins: [
                require('autoprefixer'),
                require('cssnano')({
                    preset: 'default',
                }),
            ],
        },
    },
    // Konfigurasi optimizeDeps
    optimizeDeps: {
        include: [
            'chart.js',
            'axios',
            'bootstrap',
            'lodash',
            'moment',
        ],
    },
    // Konfigurasi untuk production
    define: {
        __VUE_OPTIONS_API__: true,
        __VUE_PROD_DEVTOOLS__: false,
    },
});
