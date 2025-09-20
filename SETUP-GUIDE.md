# LMS Trimurti Husada - Setup Guide

## 🎯 Status Aplikasi

✅ **APLIKASI SUDAH BERJALAN**  
✅ **LOGIN BERHASIL**  
✅ **DATABASE TERKONEKSI**  
✅ **SEEDER DATA LENGKAP**  

---

## 🌐 Informasi Akses

**URL Aplikasi:** http://127.0.0.1:8000  
**URL Login:** http://127.0.0.1:8000/login  

### 👥 Akun Test yang Tersedia:

| Role | Email | Password | Akses |
|------|-------|----------|-------|
| **Admin** | admin@test.com | password | Dashboard Admin, User Management, Reports |
| **Guru** | guru@test.com | password | Materials, Assignments, Practicals, Scoring |
| **Siswa** | siswa@test.com | password | View Materials, Submit Assignments, View Scores |

---

## 🛠️ Status Sistem

### ✅ Sudah Berjalan:
- Laravel 12.28.1 + PHP 8.2.12
- MySQL Database Connection
- User Authentication & Authorization
- Role-based Access Control
- File Storage System
- Asset Building (Vite)

### ⚠️ Perlu Optimasi:
- **PHP Extensions:** GD dan ZIP belum aktif
- **Performance:** Config & Route belum di-cache
- **Security:** Debug mode masih aktif

---

## 🔧 Cara Mengaktifkan Extensions PHP (Opsional)

### Untuk XAMPP:
1. Buka file `C:\xampp\php\php.ini`
2. Cari dan hapus semicolon (;) di awal baris:
   ```ini
   ;extension=gd
   ;extension=zip
   ```
   Menjadi:
   ```ini
   extension=gd
   extension=zip
   ```
3. Restart Apache di XAMPP Control Panel

### Manfaat Extensions:
- **GD:** Upload dan manipulasi gambar
- **ZIP:** Export data ke Excel/PDF

---

## ⚡ Optimasi Performance

### 1. Cache Configuration (Production):
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Optimize untuk Development:
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 🎮 Command Utilities

### Health Check:
```bash
php artisan app:health
```

### Create Test Users:
```bash
php artisan create:test-users
```

### Debug Tables:
```bash
php artisan debug:notifications
php artisan debug:users
```

### Database Operations:
```bash
php artisan migrate:status
php artisan db:seed
```

---

## 📁 Struktur Direktori

```
lms-trimurti/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/     # Admin controllers
│   │   ├── Guru/      # Teacher controllers
│   │   └── Siswa/     # Student controllers
│   └── Models/        # Database models
├── resources/views/
│   ├── admin/         # Admin views
│   ├── guru/          # Teacher views
│   └── siswa/         # Student views
├── database/
│   ├── migrations/    # Database schema
│   └── seeders/       # Sample data
└── public/            # Web accessible files
```

---

## 🔄 Cara Menjalankan Server

### Development Server:
```bash
php artisan serve
```

### Dengan NPM (Hot Reload):
```bash
npm run dev
```

### Build untuk Production:
```bash
npm run build
```

---

## 🚨 Troubleshooting

### Jika Server Tidak Bisa Diakses:
1. Pastikan server berjalan: `php artisan serve`
2. Cek port 8000 tidak digunakan aplikasi lain
3. Cek firewall Windows

### Jika Database Error:
1. Pastikan MySQL/XAMPP berjalan
2. Cek koneksi: `php artisan tinker --execute="DB::connection()->getPdo();"`
3. Jalankan migration: `php artisan migrate`

### Jika Login Gagal:
1. Cek user test: `php artisan create:test-users`
2. Clear cache: `php artisan cache:clear`

---

## 🎯 Fitur Utama LMS

### 👨‍💼 Admin Features:
- Dashboard dengan statistik lengkap
- User management (CRUD users)
- Content management (materials, assignments)
- Attendance management
- Comprehensive reports
- System settings

### 👨‍🏫 Guru Features:
- Personal dashboard
- Create & manage materials
- Create & grade assignments
- Manage practical sessions
- Student attendance
- Performance reports

### 👨‍🎓 Siswa Features:
- View learning materials
- Submit assignments
- View grades & feedback
- Attendance records
- Personal progress tracking

---

## 📞 Support

Jika mengalami masalah:
1. Jalankan health check: `php artisan app:health`
2. Cek log errors: `tail -f storage/logs/laravel.log`
3. Restart server dan clear cache

---

**LMS Trimurti Husada v1.0**  
*Ready for Development & Testing* 🚀