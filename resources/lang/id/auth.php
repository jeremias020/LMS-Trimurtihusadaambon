<?php

return [
    'failed' => 'Kredensial ini tidak cocok dengan catatan kami.',
    'password' => 'Kata sandi yang diberikan salah.',
    'throttle' => 'Terlalu banyak upaya login. Silakan coba lagi dalam :seconds detik.',

    'login' => [
        'title' => 'Masuk ke Sistem',
        'email' => 'Alamat Email',
        'password' => 'Kata Sandi',
        'remember' => 'Ingat Saya',
        'forgot' => 'Lupa Kata Sandi?',
        'button' => 'Masuk',
        'no_account' => 'Tidak punya akun?',
        'register' => 'Daftar di sini',
    ],

    'register' => [
        'title' => 'Daftar Akun Baru',
        'name' => 'Nama Lengkap',
        'email' => 'Alamat Email',
        'password' => 'Kata Sandi',
        'confirm_password' => 'Konfirmasi Kata Sandi',
        'role' => 'Jenis Pengguna',
        'button' => 'Daftar',
        'have_account' => 'Sudah punya akun?',
        'login' => 'Masuk di sini',
    ],

    'verify' => [
        'title' => 'Verifikasi Alamat Email',
        'message' => 'Tautan verifikasi baru telah dikirim ke alamat email Anda.',
        'resend' => 'Kirim Ulang Email Verifikasi',
        'before_proceeding' => 'Silakan periksa kotak masuk email Anda dan klik tautan verifikasi sebelum melanjutkan.',
        'not_receive' => 'Jika Anda tidak menerima email',
    ],

    'reset' => [
        'title' => 'Reset Kata Sandi',
        'email' => 'Alamat Email',
        'password' => 'Kata Sandi Baru',
        'confirm_password' => 'Konfirmasi Kata Sandi Baru',
        'button' => 'Reset Kata Sandi',
    ],

    'forgot' => [
        'title' => 'Lupa Kata Sandi',
        'email' => 'Alamat Email',
        'button' => 'Kirim Tautan Reset Kata Sandi',
        'message' => 'Lupa kata sandi Anda? Tidak masalah. Beri tahu kami alamat email Anda dan kami akan mengirimkan tautan reset kata sandi kepada Anda.',
    ],

    'logout' => 'Keluar',

    // Password reset responses (used by Laravel's Password Broker)
    'passwords' => [
        'reset' => 'Kata sandi Anda telah direset!',
        'sent' => 'Kami telah mengirimkan tautan reset kata sandi ke email Anda!',
        'throttled' => 'Harap tunggu sebelum mencoba lagi.',
        'token' => 'Token reset kata sandi ini tidak valid.',
        'user' => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.',
    ],

    // Optional: Role translations
    'roles' => [
        'student' => 'Siswa',
        'teacher' => 'Guru',
        'admin' => 'Administrator',
    ],

    // Optional: For global consistency
    'remember_me' => 'Ingat Saya',
];
