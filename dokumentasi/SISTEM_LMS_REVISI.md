# 🏥 LMS SMK Kesehatan Trimurti Husada - Sistem Revisi

## 📋 **OVERVIEW SISTEM BARU**

Sistem LMS yang disederhanakan dan fokus pada kebutuhan spesifik SMK Kesehatan dengan 3 role utama:
- **Admin**: Kelola data master dan sistem notifikasi
- **Guru**: Upload konten dan penilaian praktik kesehatan
- **Siswa**: Akses pembelajaran dan monitoring nilai

---

## 🔐 **ADMIN FEATURES (Disederhanakan)**

### **1. Manajemen Data Master**
#### **User Management**
- ✅ **CRUD Guru**
  - Tambah/Edit/Hapus data guru
  - Assign guru ke mata pelajaran
  - Manage status aktif/nonaktif guru
  
- ✅ **CRUD Siswa**
  - Tambah/Edit/Hapus data siswa
  - Assign siswa ke kelas dan jurusan
  - Import/Export data siswa via Excel
  - Manage status aktif/nonaktif siswa

#### **Academic Structure Management**
- ✅ **Kelola Kelas**
  - CRUD kelas (X, XI, XII)
  - Set kapasitas kelas
  - Assign wali kelas
  
- ✅ **Kelola Jurusan**
  - CRUD jurusan kesehatan (Keperawatan, Farmasi, Analis Kesehatan, dll)
  - Set mata pelajaran per jurusan
  - Manage kurikulum jurusan

### **2. Kriteria Penilaian Praktik Kesehatan**
- ✅ **Master Kriteria Penilaian**
  - Buat template kriteria untuk berbagai praktik kesehatan
  - Set bobot penilaian per kriteria
  - Kategori: Persiapan, Pelaksanaan, Hasil, Sikap Profesional
  
- ✅ **Standard Operating Procedure (SOP)**
  - Upload SOP untuk setiap jenis praktik
  - Set checklist penilaian berdasarkan SOP
  - Rubrik penilaian 1-4 (Kurang, Cukup, Baik, Sangat Baik)

### **3. Jadwal Ujian & Notifikasi Otomatis**
- ✅ **CRUD Jadwal Ujian**
  - Input jadwal ujian (tanggal, waktu, mata pelajaran, kelas)
  - Set jenis ujian (UTS, UAS, Quiz, Praktik)
  - Assign pengawas ujian
  
- ✅ **Sistem Notifikasi Otomatis**
  - **H-7**: Notifikasi persiapan ujian
  - **H-3**: Reminder ujian akan datang
  - **H-1**: Final reminder ujian besok
  - **H-0**: Notifikasi ujian hari ini
  - Notifikasi muncul di dashboard guru dan siswa

### **4. Dashboard Admin**
- ✅ **Statistics Overview**
  - Total guru/siswa aktif
  - Jadwal ujian mendatang
  - Status sistem notifikasi
  - Quick actions untuk data master

---

## 👨‍🏫 **GURU FEATURES (Fokus Pembelajaran)**

### **1. Upload & Manajemen Materi**
- ✅ **Upload Materi Pembelajaran**
  - Upload file (PDF, PPT, Video, Audio)
  - Kategorisasi per mata pelajaran dan kelas
  - Set visibility (Public/Private)
  - Preview materi sebelum publish

### **2. Soal & Quiz Management**
- ✅ **Upload Soal/Quiz**
  - Upload soal dalam format PDF/Word
  - Set deadline pengumpulan
  - Assign ke kelas tertentu
  - Set jenis soal (Quiz, Tugas, UTS, UAS)

### **3. Penilaian Praktik Otomatis**
- ✅ **Integrated Assessment System**
  - Gunakan kriteria penilaian dari admin
  - Form penilaian otomatis berdasarkan SOP
  - Checklist penilaian per kriteria
  - Real-time scoring calculation
  
- ✅ **Auto-Submit & Feedback**
  - **Submit nilai** → Otomatis kirim ke database
  - **Auto-generate feedback** berdasarkan nilai per kriteria
  - **Instant notification** ke siswa via dashboard
  - **Auto-generate certificate** jika lulus praktik

### **4. Absensi Siswa**
- ✅ **Digital Attendance**
  - Input absensi per sesi pembelajaran
  - Bulk attendance input
  - Set status: Hadir, Izin, Sakit, Alpha
  - Export laporan absensi per periode

### **5. Dashboard Guru**
- ✅ **Teaching Overview**
  - Kelas yang diampu
  - Jadwal mengajar hari ini
  - Pending penilaian praktik
  - Notifikasi ujian yang diawasi

---

## 👨‍🎓 **SISWA FEATURES (Akses Pembelajaran)**

### **1. Akses Materi Pembelajaran**
- ✅ **Material Library**
  - **View** materi per mata pelajaran
  - **Download** materi yang diperlukan
  - **Search** materi berdasarkan keyword
  - **History** download untuk tracking

### **2. Soal & Quiz Access**
- ✅ **Assignment Center**
  - **Lihat** daftar soal/quiz yang tersedia
  - **Download** soal dalam berbagai format
  - **Upload** jawaban yang sudah dikerjakan
  - **Track status** (Pending, Submitted, Graded)

### **3. Submission System**
- ✅ **Answer Submission**
  - Upload jawaban quiz/tugas
  - Set deadline notification
  - Re-upload jika masih dalam batas waktu
  - Confirmation email setelah submit

### **4. Laporan Nilai & Absensi**
- ✅ **Personal Academic Report**
  - **Laporan Nilai Praktik**
    - Nilai per praktik dengan breakdown kriteria
    - Feedback otomatis dari guru
    - Grafik progress penilaian
    - **Download PDF** laporan nilai
  
  - **Laporan Absensi**
    - Absensi per mata pelajaran
    - Statistik kehadiran (%)
    - History izin/sakit/alpha
    - **Download PDF** laporan absensi

### **5. Dashboard Siswa**
- ✅ **Student Portal**
  - Notifikasi ujian mendatang
  - Quick access ke materi terbaru
  - Progress nilai praktik
  - Reminder tugas belum dikerjakan

---

## 🔄 **SISTEM NOTIFIKASI OTOMATIS**

### **Notifikasi Jadwal Ujian**
```
Admin Input Jadwal → Sistem Schedule Notifications → Auto Notify Users

Timeline Notifikasi:
├── H-7: "📅 Ujian [Mata Pelajaran] akan dilaksanakan dalam 7 hari"
├── H-3: "⏰ Reminder: Ujian [Mata Pelajaran] 3 hari lagi"  
├── H-1: "🚨 Besok ujian [Mata Pelajaran] - Persiapkan diri!"
└── H-0: "📝 Hari ini ujian [Mata Pelajaran] jam [waktu]"
```

### **Feedback Nilai Praktik Otomatis**
```
Guru Submit Nilai → Auto Calculate → Generate Feedback → Send to Student

Contoh Output:
"🎯 Nilai Praktik Anda: 85/100
📊 Detail:
- Persiapan: 90% (Sangat Baik)
- Pelaksanaan: 85% (Baik) 
- Hasil: 80% (Baik)
- Sikap: 85% (Baik)

💬 Feedback: Performa praktik sudah baik, tingkatkan ketelitian dalam pelaksanaan prosedur."
```

---

## 🎯 **WORKFLOW SISTEM BARU**

### **1. Admin Workflow**
```
Setup Data Master → Input Kriteria Penilaian → Schedule Ujian → Monitor Sistem
```

### **2. Guru Workflow**  
```
Upload Materi → Upload Soal → Penilaian Praktik → Submit Nilai → Auto Feedback
```

### **3. Siswa Workflow**
```
Akses Materi → Download Soal → Submit Jawaban → Terima Feedback → Download Laporan
```

### **4. System Automation**
```
Schedule Monitoring → Auto Notifications → Grade Processing → Report Generation
```

---

## 📊 **FITUR COMPARISON - BEFORE vs AFTER**

| **Aspek** | **Sistem Lama** | **Sistem Baru** |
|-----------|------------------|------------------|
| **Admin Scope** | 45+ fitur kompleks | 4 core functions |
| **Guru Focus** | Content management | Teaching & Assessment |
| **Siswa Access** | 20+ scattered features | 4 essential functions |
| **Notifications** | Manual | Fully Automated |
| **Assessment** | Manual grading | Auto-integrated scoring |
| **Reports** | Multiple complex reports | Essential PDF reports |

---

## 🛠️ **TECHNICAL SPECIFICATIONS**

### **Database Requirements**
- **Users**: id, name, email, role, kelas_id, jurusan_id, status
- **Kelas**: id, nama, tingkat, jurusan_id, kapasitas
- **Jurusan**: id, nama, deskripsi, mata_pelajaran
- **Kriteria_Penilaian**: id, nama, bobot, kategori, deskripsi
- **Jadwal_Ujian**: id, tanggal, mata_pelajaran, kelas_id, jenis_ujian
- **Materials**: id, title, file_path, mata_pelajaran, kelas_id, visibility
- **Assignments**: id, title, file_path, deadline, kelas_id, status
- **Nilai_Praktik**: id, siswa_id, kriteria_id, nilai, feedback, tanggal
- **Absensi**: id, siswa_id, tanggal, status, mata_pelajaran

### **Notification System**
- **Laravel Jobs & Queues** untuk scheduled notifications
- **Database Notifications** untuk in-app alerts
- **Event Broadcasting** untuk real-time updates

### **File Management**
- **Storage path**: `/storage/materials/`, `/storage/assignments/`
- **Allowed formats**: PDF, DOC, PPT, MP4, MP3, JPG, PNG
- **Max file size**: 50MB per upload

---

## 🚀 **IMPLEMENTATION PRIORITY**

### **Phase 1 - Core Setup (Week 1-2)**
1. Database migration untuk struktur baru
2. Admin panel untuk data master
3. Basic upload system untuk guru

### **Phase 2 - Assessment System (Week 3-4)**  
1. Kriteria penilaian praktik
2. Auto-scoring system
3. Feedback generation

### **Phase 3 - Notification & Reports (Week 5-6)**
1. Automated notification system
2. PDF report generation
3. Student dashboard integration

### **Phase 4 - Testing & Deployment (Week 7-8)**
1. System testing
2. User acceptance testing
3. Production deployment

---

**Status**: Proposal Sistem Baru ✅  
**Target Users**: Admin (5), Guru (30), Siswa (500)  
**Expected Go-Live**: Oktober 2025