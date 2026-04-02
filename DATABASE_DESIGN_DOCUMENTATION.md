# Dokumentasi Perancangan Database LMS Trimurti Husada

## 📋 Overview
Sistem Learning Management System (LMS) untuk SMK Kesehatan Trimurti Husada Ambon yang dirancang dengan Laravel 12.0 dan MySQL database.

---

## 🗄️ Struktur Database

### **Tabel Utama Pengguna (Users)**

#### **1. `users`**
- **Tujuan**: Tabel autentikasi utama untuk semua role
- **Kolom**:
  ```sql
  - id (PK)
  - name (VARCHAR) - Nama lengkap pengguna
  - email (VARCHAR, UNIQUE) - Login email
  - password (VARCHAR) - Hashed password
  - role (ENUM) - admin, guru, siswa
  - nis_nip (VARCHAR) - NIS untuk siswa, NIP untuk guru
  - phone (VARCHAR) - Nomor telepon
  - avatar (VARCHAR) - Path foto profil
  - is_active (BOOLEAN) - Status aktif/non-aktif
  - created_at, updated_at, deleted_at
  ```

#### **2. `admins`**
- **Tujuan**: Data spesifik admin
- **Kolom**:
  ```sql
  - id (PK)
  - user_id (FK → users.id) - Relasi ke users
  - nip (VARCHAR) - NIP admin
  - created_at, updated_at
  ```

#### **3. `gurus`**
- **Tujuan**: Data spesifik guru
- **Kolom**:
  ```sql
  - id (PK)
  - user_id (FK → users.id) - Relasi ke users
  - nip (VARCHAR) - NIP guru
  - created_at, updated_at
  ```

#### **4. `siswa`**
- **Tujuan**: Data spesifik siswa (terpisah dari users untuk data akademik)
- **Kolom**:
  ```sql
  - id (PK)
  - user_id (FK → users.id) - Relasi ke users
  - nis (VARCHAR) - Nomor Induk Siswa
  - nisn (VARCHAR) - NISN nasional
  - jenis_kelamin (ENUM) - L, P
  - tempat_lahir (VARCHAR)
  - tanggal_lahir (DATE)
  - alamat (TEXT)
  - no_telepon (VARCHAR)
  - kelas_id (FK → classes.id) - Kelas saat ini
  - major (VARCHAR) - Kode jurusan (relasi ke majors.code)
  - tahun_ajaran (VARCHAR) - Tahun ajaran aktif
  - nama_ortu (VARCHAR) - Nama orang tua
  - no_telepon_ortu (VARCHAR) - Kontak orang tua
  - golongan_darah (VARCHAR) - Golongan darah
  - riwayat_penyakit (TEXT) - Riwayat medis
  - alergi (TEXT) - Data alergi
  - info_kesehatan (TEXT) - Info kesehatan lainnya
  - foto (VARCHAR) - Path foto siswa
  - status (ENUM) - active, inactive, graduated
  - created_at, updated_at, deleted_at
  ```

---

### **Tabel Akademik**

#### **5. `majors`**
- **Tujuan**: Data jurusan/program keahlian
- **Kolom**:
  ```sql
  - id (PK)
  - name (VARCHAR) - Nama jurusan (ex: Keperawatan)
  - code (VARCHAR, UNIQUE) - Kode jurusan (ex: KEP)
  - description (TEXT) - Deskripsi jurusan
  - created_at, updated_at
  ```

#### **6. `classes`**
- **Tujuan**: Data kelas/rombongan belajar
- **Kolom**:
  ```sql
  - id (PK)
  - name (VARCHAR) - Nama kelas (ex: XII Keperawatan A)
  - code (VARCHAR) - Kode kelas
  - major_id (FK → majors.id) - Jurusan kelas
  - grade (VARCHAR) - Tingkat kelas (X, XI, XII)
  - academic_year (VARCHAR) - Tahun ajaran
  - wallpaper (VARCHAR) - Background kelas
  - created_at, updated_at
  ```

#### **7. `subjects`**
- **Tujuan**: Data mata pelajaran
- **Kolom**:
  ```sql
  - id (PK)
  - name (VARCHAR) - Nama mata pelajaran
  - code (VARCHAR, UNIQUE) - Kode mata pelajaran
  - description (TEXT) - Deskripsi mata pelajaran
  - major_id (FK → majors.id, NULLABLE) - Jurusan terkait
  - guru_id (FK → gurus.id, NULLABLE) - Guru pengampu
  - kelas_id (FK → classes.id, NULLABLE) - Kelas terkait
  - type (ENUM) - teori, praktikum, campuran
  - is_active (BOOLEAN) - Status aktif
  - sks (INTEGER) - Satuan Kredit Semester
  - color (VARCHAR) - Warna untuk UI
  - order (INTEGER) - Urutan tampilan
  - created_at, updated_at, deleted_at
  ```

#### **8. `class_subjects`**
- **Tujuan**: Relasi many-to-many antara kelas dan mata pelajaran
- **Kolom**:
  ```sql
  - id (PK)
  - class_id (FK → classes.id)
  - subject_id (FK → subjects.id)
  - guru_id (FK → gurus.id) - Guru pengampu untuk kelas ini
  - created_at, updated_at
  ```

#### **9. `class_students`**
- **Tujuan**: Relasi many-to-many antara kelas dan siswa
- **Kolom**:
  ```sql
  - id (PK)
  - class_id (FK → classes.id)
  - student_id (FK → siswa.id)
  - academic_year (VARCHAR) - Tahun ajaran
  - status (ENUM) - active, moved, graduated
  - created_at, updated_at
  ```

---

### **Tabel Pembelajaran**

#### **10. `materials`**
- **Tujuan**: Materi pembelajaran
- **Kolom**:
  ```sql
  - id (PK)
  - subject_id (FK → subjects.id)
  - guru_id (FK → gurus.id)
  - kelas_id (FK → classes.id, NULLABLE)
  - title (VARCHAR) - Judul materi
  - description (TEXT) - Deskripsi materi
  - file_path (VARCHAR) - Path file materi
  - file_type (ENUM) - pdf, ppt, video, audio, link
  - file_size (INTEGER) - Ukuran file dalam bytes
  - is_public (BOOLEAN) - Bisa diakses semua kelas
  - order (INTEGER) - Urutan materi
  - created_at, updated_at
  ```

#### **11. `assignments`**
- **Tujuan**: Tugas/pekerjaan rumah
- **Kolom**:
  ```sql
  - id (PK)
  - subject_id (FK → subjects.id)
  - guru_id (FK → gurus.id)
  - kelas_id (FK → classes.id)
  - title (VARCHAR) - Judul tugas
  - description (TEXT) - Deskripsi tugas
  - file_path (VARCHAR) - File soal (jika ada)
  - due_date (DATETIME) - Batas pengumpulan
  - max_score (DECIMAL) - Nilai maksimal
  - is_published (BOOLEAN) - Status publikasi
  - allow_late_submission (BOOLEAN) - Izinkan telat
  - created_at, updated_at
  ```

#### **12. `assignment_submissions`**
- **Tujuan**: Pengumpulan tugas siswa
- **Kolom**:
  ```sql
  - id (PK)
  - assignment_id (FK → assignments.id)
  - student_id (FK → siswa.id)
  - file_path (VARCHAR) - Path file jawaban
  - submitted_at (DATETIME) - Waktu pengumpulan
  - score (DECIMAL) - Nilai yang diberikan
  - feedback (TEXT) - Feedback guru
  - status (ENUM) - submitted, graded, returned
  - created_at, updated_at
  ```

#### **13. `exam_schedules_new`**
- **Tujuan**: Jadwal ujian
- **Kolom**:
  ```sql
  - id (PK)
  - subject_id (FK → subjects.id)
  - kelas_id (FK → classes.id)
  - guru_id (FK → gurus.id)
  - exam_type (ENUM) - uts, uas, praktik, quiz
  - title (VARCHAR) - Judul ujian
  - description (TEXT) - Deskripsi ujian
  - exam_date (DATETIME) - Tanggal ujian
  - duration (INTEGER) - Durasi dalam menit
  - room (VARCHAR) - Ruangan ujian
  - max_score (DECIMAL) - Nilai maksimal
  - is_published (BOOLEAN) - Status publikasi
  - created_at, updated_at
  ```

---

### **Tabel Penilaian**

#### **14. `assessment_criteria`**
- **Tujuan**: Kriteria penilaian
- **Kolom**:
  ```sql
  - id (PK)
  - name (VARCHAR) - Nama kriteria
  - description (TEXT) - Deskripsi kriteria
  - type (ENUM) - theoretical, practical, attitude, skill
  - max_score (DECIMAL) - Nilai maksimal
  - weight (DECIMAL) - Bobat penilaian
  - is_active (BOOLEAN) - Status aktif
  - created_at, updated_at
  ```

#### **15. `practical_assessments`**
- **Tujuan**: Penilaian praktikum
- **Kolom**:
  ```sql
  - id (PK)
  - student_id (FK → siswa.id)
  - subject_id (FK → subjects.id)
  - guru_id (FK → gurus.id)
  - assessment_date (DATE) - Tanggal penilaian
  - criteria_scores (JSON) - Nilai per kriteria
  - total_score (DECIMAL) - Total nilai
  - notes (TEXT) - Catatan penilaian
  - status (ENUM) - draft, submitted, approved
  - created_at, updated_at
  ```

---

### **Tabel Kehadiran**

#### **16. `attendances`**
- **Tujuan**: Data kehadiran
- **Kolom**:
  ```sql
  - id (PK)
  - student_id (FK → siswa.id)
  - subject_id (FK → subjects.id)
  - kelas_id (FK → classes.id)
  - guru_id (FK → gurus.id)
  - attendance_date (DATE) - Tanggal kehadiran
  - status (ENUM) - hadir, sakit, izin, alpa
  - notes (TEXT) - Catatan/keterangan
  - check_in_time (TIME) - Waktu check-in
  - created_at, updated_at
  ```

---

### **Tabel Sistem**

#### **17. `notifications`**
- **Tujuan**: Notifikasi sistem
- **Kolom**:
  ```sql
  - id (PK)
  - user_id (FK → users.id, NULLABLE) - Penerima notifikasi
  - title (VARCHAR) - Judul notifikasi
  - message (TEXT) - Isi notifikasi
  - type (ENUM) - info, success, warning, error
  - is_read (BOOLEAN) - Status dibaca
  - action_url (VARCHAR) - URL untuk aksi
  - created_at, updated_at
  ```

#### **18. `migrations`**
- **Tujuan**: Log migrasi database Laravel
- **Kolom**: Standard Laravel migration table

#### **19. `users_central`**
- **Tujuan**: Tabel terpusat user (kemungkinan untuk multi-instansi)
- **Kolom**: Mirip dengan users table

---

## 🔄 Relasi Database

### **One-to-Many Relationships**
```
users (1) → (N) admins
users (1) → (N) gurus  
users (1) → (N) siswa

majors (1) → (N) classes
majors (1) → (N) subjects

classes (1) → (N) class_subjects
classes (1) → (N) class_students
classes (1) → (N) materials
classes (1) → (N) assignments
classes (1) → (N) attendances

subjects (1) → (N) materials
subjects (1) → (N) assignments
subjects (1) → (N) exam_schedules_new
subjects (1) → (N) attendances
subjects (1) → (N) practical_assessments

gurus (1) → (N) materials
gurus (1) → (N) assignments
gurus (1) → (N) exam_schedules_new
gurus (1) → (N) attendances
gurus (1) → (N) practical_assessments

siswa (1) → (N) assignment_submissions
siswa (1) → (N) attendances
siswa (1) → (N) practical_assessments
```

### **Many-to-Many Relationships**
```
classes ↔ subjects (through class_subjects)
classes ↔ siswa (through class_students)
```

### **Special Relationships**
```
siswa.major → majors.code (String relationship, not FK)
siswa.kelas_id → classes.id (Current class)
```

---

## 🎯 Design Patterns & Best Practices

### **1. Soft Deletes**
- Tables dengan soft deletes: `users`, `siswa`, `subjects`
- Menggunakan `deleted_at` timestamp
- Memungkinkan recovery data

### **2. Polymorphic Relationships**
- `notifications` bisa untuk semua user types
- `materials` bisa untuk multiple classes

### **3. Eager Loading Optimization**
- Relationships dimuat dengan `load()` dan `with()`
- Menghindari N+1 query problems

### **4. Data Integrity**
- Foreign key constraints untuk relasi data
- ENUM types untuk data dengan nilai tetap
- Unique constraints untuk kode/nama unik

### **5. Audit Trail**
- `created_at`, `updated_at` untuk tracking perubahan
- `submitted_at` untuk tracking pengumpulan tugas

---

## 🔍 Index & Performance

### **Recommended Indexes**
```sql
-- Users
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_active ON users(is_active);

-- Academic
CREATE INDEX idx_majors_code ON majors(code);
CREATE INDEX idx_classes_major ON classes(major_id);
CREATE INDEX idx_classes_grade ON classes(grade);
CREATE INDEX idx_subjects_type ON subjects(type);
CREATE INDEX idx_subjects_active ON subjects(is_active);

-- Learning
CREATE INDEX idx_materials_subject ON materials(subject_id);
CREATE INDEX idx_assignments_due ON assignments(due_date);
CREATE INDEX idx_attendances_date ON attendances(attendance_date);
CREATE INDEX idx_attendances_student ON attendances(student_id);

-- Notifications
CREATE INDEX idx_notifications_user ON notifications(user_id);
CREATE INDEX idx_notifications_read ON notifications(is_read);
```

---

## 📊 Data Flow

### **1. User Registration Flow**
```
1. Create user account (users table)
2. Create role-specific data (admins/gurus/siswa)
3. Set initial permissions and status
```

### **2. Academic Year Flow**
```
1. Create majors and classes
2. Assign students to classes (class_students)
3. Assign subjects to classes (class_subjects)
4. Schedule exams and assignments
```

### **3. Learning Process Flow**
```
1. Guru uploads materials
2. Students access materials by class
3. Assignments created with due dates
4. Students submit assignments
5. Guru grades and provides feedback
```

---

## 🚀 Scalability Considerations

### **1. Partitioning**
- `attendances` bisa dipartisi per tahun ajaran
- `materials` bisa dipartisi per subject

### **2. Caching Strategy**
- Cache frequently accessed data (majors, classes, subjects)
- Cache user permissions and roles
- Cache assignment due dates

### **3. Archive Strategy**
- Archive old attendance data
- Archive graduated students data
- Archive old assignment submissions

---

## 🔒 Security Considerations

### **1. Data Protection**
- Hashed passwords dengan bcrypt
- Sensitive data (health info) encrypted
- File upload validation and sanitization

### **2. Access Control**
- Role-based permissions
- Class-specific access control
- Data ownership validation

### **3. Audit Logging**
- Log semua perubahan data penting
- Track file access and downloads
- Monitor login activities

---

## 📈 Analytics & Reporting

### **1. Student Performance**
- Grade tracking per subject
- Attendance statistics
- Assignment completion rates

### **2. Teacher Performance**
- Material upload frequency
- Grading turnaround time
- Student engagement metrics

### **3. System Usage**
- Active users per role
- Feature usage statistics
- Performance metrics

---

## 🛠️ Maintenance & Backup

### **1. Regular Maintenance**
- Weekly table optimization
- Monthly data cleanup
- Quarterly index rebuild

### **2. Backup Strategy**
- Daily incremental backups
- Weekly full backups
- Monthly off-site backups

### **3. Disaster Recovery**
- Point-in-time recovery capability
- Redundant database servers
- Automated failover systems

---

## 📝 Future Enhancements

### **1. Planned Features**
- Mobile app API endpoints
- Real-time notifications
- Advanced analytics dashboard
- Integration with external systems

### **2. Scalability Improvements**
- Read replicas for reporting
- Distributed file storage
- Microservices architecture

---

## 🎯 Conclusion

Database LMS Trimurti Husada dirancang dengan:
- **Normalization** yang baik untuk menghindari redundancy
- **Flexibility** untuk berbagai skenario pembelajaran
- **Performance** yang optimal dengan proper indexing
- **Security** yang kuat dengan proper access control
- **Scalability** untuk mendukung pertumbuhan di masa depan

Design ini mendukung kebutuhan SMK Kesehatan dengan fitur khusus untuk:
- Manajemen jurusan kesehatan
- Penilaian praktikum berbasis SOP
- Tracking kesehatan siswa
- Sistem kehadiran yang komprehensif
