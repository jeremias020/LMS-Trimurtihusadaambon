# Database Schema Documentation

## Overview
Database ini dirancang untuk LMS (Learning Management System) SMK Kesehatan dengan fokus pada manajemen akademik, pembelajaran, dan penilaian praktik.

## 📊 Struktur Tabel

### A. Modul Manajemen User & Akademik (Admin)

#### 1. `users`
Menyimpan data login untuk Admin, Guru, dan Siswa.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK, AI) | ID unik |
| name | VARCHAR | Nama lengkap |
| email | VARCHAR (Unique) | Email login |
| password | VARCHAR | Password (hashed) |
| role | ENUM | 'admin', 'guru', 'siswa' |
| nis_nip | VARCHAR (Unique) | NIS (siswa) / NIP (guru) |
| phone | VARCHAR | Nomor telepon |
| avatar | VARCHAR | Path foto profil |
| is_active | BOOLEAN | Status aktif |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

#### 2. `majors` (Jurusan)
Data jurusan/kompetensi keahlian.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK, AI) | ID unik |
| name | VARCHAR | Nama jurusan (Keperawatan, Farmasi) |
| code | VARCHAR (Unique) | Kode jurusan (KEP, FAR) |
| description | TEXT | Deskripsi jurusan |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

#### 3. `classes` (Kelas)
Data kelas/rombongan belajar.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK, AI) | ID unik |
| name | VARCHAR | Nama kelas (X Keperawatan 1) |
| major_id | INT (FK) | Relasi ke majors.id |
| academic_year | VARCHAR | Tahun ajaran (2023/2024) |
| wallpaper | VARCHAR | Background kelas |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

#### 4. `class_students` (Relasi Siswa-Kelas)
Hubungan many-to-many antara siswa dan kelas.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK, AI) | ID unik |
| class_id | INT (FK) | Relasi ke classes.id |
| student_id | INT (FK) | Relasi ke users.id (siswa) |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

### B. Modul Mata Pelajaran & Jadwal (Admin & Guru)

#### 5. `subjects` (Mata Pelajaran)
Data mata pelajaran.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK, AI) | ID unik |
| name | VARCHAR | Nama mata pelajaran |
| code | VARCHAR (Unique) | Kode mata pelajaran |
| major_id | INT (FK) | Relasi ke majors.id |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

#### 6. `class_subjects` (Jadwal Mengajar/Rombel)
Pivot table penting yang menghubungkan Kelas, Mapel, dan Guru.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK, AI) | ID unik |
| class_id | INT (FK) | Relasi ke classes.id |
| subject_id | INT (FK) | Relasi ke subjects.id |
| teacher_id | INT (FK) | Relasi ke users.id (guru) |
| day | ENUM | Hari (Senin-Minggu) |
| start_time | TIME | Jam mulai |
| end_time | TIME | Jam selesai |
| room | VARCHAR | Ruangan (Lab Keperawatan 1) |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

#### 7. `assessment_criteria` (Kriteria Penilaian/KD)
Data Kompetensi Dasar atau kriteria penilaian.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK, AI) | ID unik |
| subject_id | INT (FK) | Relasi ke subjects.id |
| code | VARCHAR | Kode KD (KD 3.1) |
| name | VARCHAR | Nama kriteria |
| type | ENUM | 'knowledge', 'skill' |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

### C. Modul Pembelajaran (Guru & Siswa)

#### 8. `materials` (Materi Ajar)
Konten pembelajaran untuk setiap pertemuan.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK, AI) | ID unik |
| class_subject_id | INT (FK) | Relasi ke class_subjects.id |
| title | VARCHAR | Judul materi |
| content | LONGTEXT | Konten HTML |
| file_url | VARCHAR | File PDF/PPT |
| video_url | VARCHAR | Link video |
| published_at | TIMESTAMP | Waktu publish |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

#### 9. `assignments` (Tugas)
Data tugas/pekerjaan rumah.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK, AI) | ID unik |
| class_subject_id | INT (FK) | Relasi ke class_subjects.id |
| title | VARCHAR | Judul tugas |
| description | TEXT | Deskripsi tugas |
| file_url | VARCHAR | File soal (PDF) |
| due_date | DATETIME | Deadline |
| max_score | INT | Nilai maksimal |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

#### 10. `assignment_submissions` (Pengumpulan Tugas)
Data pengumpulan tugas oleh siswa.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK, AI) | ID unik |
| assignment_id | INT (FK) | Relasi ke assignments.id |
| student_id | INT (FK) | Relasi ke users.id |
| file_url | VARCHAR | File jawaban |
| submission_text | TEXT | Jawaban teks |
| submitted_at | TIMESTAMP | Waktu kumpul |
| score | INT | Nilai |
| feedback | TEXT | Feedback guru |
| status | ENUM | 'submitted', 'graded', 'late' |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

#### 11. `attendances` (Absensi)
Data kehadiran siswa per pertemuan.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK, AI) | ID unik |
| class_subject_id | INT (FK) | Relasi ke class_subjects.id |
| student_id | INT (FK) | Relasi ke users.id |
| date | DATE | Tanggal absensi |
| status | ENUM | 'present', 'sick', 'permission', 'alpha' |
| note | VARCHAR | Keterangan |
| created_by | INT (FK) | Relasi ke users.id (guru) |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

### D. Modul Penilaian Praktik (Khusus SMK Kesehatan)

#### 12. `practical_assessments` (Nilai Praktik)
Data penilaian praktik siswa berdasarkan kriteria.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK, AI) | ID unik |
| student_id | INT (FK) | Relasi ke users.id |
| subject_id | INT (FK) | Relasi ke subjects.id |
| criteria_id | INT (FK) | Relasi ke assessment_criteria.id |
| teacher_id | INT (FK) | Relasi ke users.id (penguji) |
| score | DECIMAL 5,2 | Nilai angka |
| assessment_date | DATE | Tanggal penilaian |
| notes | TEXT | Catatan penguji |
| evidence_url | VARCHAR | Foto/video bukti |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

## 🔗 Relasi Antar Tabel

### Primary Flow:
1. **users** (siswa) → **class_students** ← **classes** → **majors**
2. **classes** + **subjects** + **users** (guru) → **class_subjects** (Jadwal)
3. **class_subjects** → **materials**, **assignments**, **attendances**
4. **subjects** → **assessment_criteria**
5. **assignments** → **assignment_submissions**
6. **practical_assessments** menghubungkan **siswa**, **mapel**, **kriteria**, dan **guru**

## 📈 Index dan Performance

### Index yang ditambahkan:
- **users**: role, nis_nip, is_active
- **majors**: code
- **classes**: major_id, academic_year
- **subjects**: code, major_id
- **class_subjects**: class_id, subject_id, teacher_id, day
- **assessment_criteria**: subject_id, type
- **materials**: class_subject_id, published_at
- **assignments**: class_subject_id, due_date
- **assignment_submissions**: assignment_id, student_id, status
- **attendances**: class_subject_id, student_id, date, status
- **practical_assessments**: student_id, subject_id, criteria_id, teacher_id, assessment_date

### Unique Constraints:
- **class_students**: [class_id, student_id]
- **class_subjects**: [class_id, subject_id, teacher_id, day, start_time]
- **assessment_criteria**: [subject_id, code]
- **assignment_submissions**: [assignment_id, student_id]
- **attendances**: [class_subject_id, student_id, date]

## 🚀 Cara Penggunaan

### 1. Run Migration:
```bash
php artisan migrate
```

### 2. Seed Data (opsional):
```bash
php artisan db:seed
```

## 📝 Catatan Penting

1. **class_subjects** adalah tabel pivot utama yang menghubungkan jadwal, kelas, mapel, dan guru
2. **practical_assessments** dirancang khusus untuk penilaian praktik SMK Kesehatan
3. Semua foreign key menggunakan cascade delete untuk menjaga integritas data
4. Index dioptimalkan untuk query yang sering digunakan (filtering dan joining)
