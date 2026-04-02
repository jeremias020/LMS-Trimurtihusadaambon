# 🔐 **KREDENSIAL LOGIN LMS TRIMURTI HUSADA**

## 📋 **Informasi Umum**
- **URL Login**: `http://localhost:8000/login` 
- **Aplikasi**: LMS SMK Kesehatan Trimurti Husada
- **Versi**: 1.0
- **Database**: MySQL (Laravel Migration)

---

## 👑 **ADMIN (Administrator)**

### **Akun Utama**
- **Email**: `admin@trimurti.edu`
- **Password**: `admin123`
- **Username**: `admin`
- **Role**: Administrator
- **Status**: Active
- **Dashboard**: `/admin/dashboard`

### **Akses & Fitur Admin:**
- ✅ **Manajemen Users** - CRUD semua pengguna (Admin/Guru/Siswa)
- ✅ **Manajemen Master Data** - Kelas, Jurusan, Kriteria Penilaian
- ✅ **Jadwal Ujian** - Input dan manajemen jadwal dengan notifikasi otomatis
- ✅ **System Settings** - Pengaturan sistem global
- ✅ **Reports** - Laporan sistem komprehensif
- ✅ **User Status Management** - Aktifkan/nonaktifkan pengguna
- ✅ **Backup & Restore** - Maintenance database

---

## 👨‍🏫 **GURU (Teachers)**

### **Akun Guru 1**
- **Email**: `guru@trimurti.edu`
- **Password**: `guru123`
- **Username**: `guru1`
- **Nama**: Dr. Sari Kusuma, S.Kep., M.Kep
- **Role**: Guru
- **Gender**: Perempuan
- **Status**: Active
- **Dashboard**: `/guru/dashboard`

### **Akun Guru 2**
- **Email**: `guru2@trimurti.edu`
- **Password**: `guru123`
- **Username**: `guru2`
- **Nama**: Ns. Budi Santoso, S.Kep., M.Kep
- **Role**: Guru
- **Gender**: Laki-laki
- **Status**: Active
- **Dashboard**: `/guru/dashboard`

### **Akses & Fitur Guru:**
- ✅ **Upload Materi** - PDF, PPT, Video, Audio
- ✅ **Manajemen Soal/Quiz** - Buat dan kelola tugas
- ✅ **Penilaian Praktik** - Sistem penilaian terintegrasi berdasarkan SOP
- ✅ **Absensi Siswa** - Input dan laporan kehadiran
- ✅ **Auto Feedback** - Generate feedback otomatis untuk siswa
- ✅ **Laporan Komprehensif** - Absensi, praktik, tugas, nilai
- ✅ **Grading System** - Penilaian dengan rubrik

---

## 👨‍🎓 **SISWA (Students)**

### **Akun Siswa 1**
- **Email**: `siswa@trimurti.edu`
- **Password**: `siswa123`
- **Username**: `siswa1`
- **Nama**: Andi Pratama
- **Role**: Siswa
- **Gender**: Laki-laki
- **Kelas**: X Keperawatan A
- **Status**: Active
- **Dashboard**: `/siswa/dashboard`

### **Akun Siswa 2**
- **Email**: `siswa2@trimurti.edu`
- **Password**: `siswa123`
- **Username**: `siswa2`
- **Nama**: Siti Nurhayati
- **Role**: Siswa
- **Gender**: Perempuan
- **Kelas**: X Keperawatan A
- **Status**: Active
- **Dashboard**: `/siswa/dashboard`

### **Akun Siswa 3**
- **Email**: `siswa3@trimurti.edu`
- **Password**: `siswa123`
- **Username**: `siswa3`
- **Nama**: Made Wijaya
- **Role**: Siswa
- **Gender**: Laki-laki
- **Kelas**: X Keperawatan A
- **Status**: Active
- **Dashboard**: `/siswa/dashboard`

### **Akses & Fitur Siswa:**
- ✅ **Material Library** - Akses dan download materi pembelajaran
- ✅ **Assignment Center** - Lihat dan submit tugas
- ✅ **Praktikum** - Akses praktikum dan submit hasil
- ✅ **Laporan Pribadi** - Nilai praktik dan absensi dalam PDF
- ✅ **Progress Tracking** - Monitor kemajuan pembelajaran
- ✅ **Notifikasi** - Reminder ujian dan tugas

---

## 🏫 **DATA KELAS TERSEDIA**

1. **X Keperawatan A** (Kode: X-KEP-A) - Kapasitas: 30
2. **X Keperawatan B** (Kode: X-KEP-B) - Kapasitas: 30  
3. **XI Keperawatan A** (Kode: XI-KEP-A) - Kapasitas: 28
4. **XII Keperawatan A** (Kode: XII-KEP-A) - Kapasitas: 25
5. **X Farmasi** (Kode: X-FAR) - Kapasitas: 25
6. **XI Farmasi** (Kode: XI-FAR) - Kapasitas: 23

---

## 🚀 **CARA LOGIN**

### **Langkah-langkah Login:**
1. Buka browser dan akses `http://localhost:8000/login`
2. **Pilih Role** dari dropdown (Admin/Guru/Siswa)
3. **Masukkan Email** sesuai role yang dipilih
4. **Masukkan Password** yang sesuai
5. Klik **"Masuk"** untuk login
6. Anda akan diarahkan ke dashboard sesuai role

### **Tips Login:**
- ⚠️ **Pastikan memilih role yang tepat** sebelum memasukkan kredensial
- ✅ **Gunakan "Ingat saya"** untuk login otomatis di masa depan
- 🔄 **Klik "Lupa kata sandi"** jika perlu reset password
- 📱 **Responsif** - Bisa diakses dari desktop dan mobile

---

## ⚙️ **TROUBLESHOOTING**

### **Jika Login Gagal:**
1. **Periksa Role** - Pastikan role dropdown sudah dipilih dengan benar
2. **Periksa Email** - Pastikan menggunakan email yang tepat (@trimurti.edu)
3. **Periksa Password** - Password bersifat case-sensitive
4. **Clear Cache** - Hapus cache browser jika perlu
5. **Refresh Halaman** - Coba reload halaman login

### **Error Database:**
Jika ada error database, jalankan:
```bash
php artisan migrate:refresh --seed
php artisan db:seed --class=DefaultUsersSeeder
```

---

## 🔧 **COMMAND UNTUK RESET DATA**

```bash
# Reset dan seed ulang user default
php artisan db:seed --class=DefaultUsersSeeder

# Atau reset seluruh database
php artisan migrate:fresh --seed
```

---

## 📞 **SUPPORT**

Jika ada masalah atau pertanyaan:
- **Developer**: LMS Development Team
- **Institution**: SMK Kesehatan Trimurti Husada Ambon
- **Version**: 1.0 (September 2025)

---

**✅ Semua akun telah berhasil dibuat dan siap digunakan!**

**🔐 Total Akun:**
- 1 Administrator
- 2 Guru  
- 3 Siswa
- 6 Kelas tersedia

**🎯 Akses sekarang di:** `http://localhost:8000/login`