# 🧹 LMS System Cleanup Recommendations

## 📋 **AUDIT RESULTS SUMMARY**

Berdasarkan audit menyeluruh terhadap sistem LMS dan requirement baru yang telah ditetapkan, berikut adalah rekomendasi cleanup untuk mengoptimalkan sistem.

---

## ❌ **FILES TO DELETE (Dapat Dihapus)**

### **1. Redundant Dashboard Files**
```bash
# File dashboard yang duplikat/lama
resources/views/admin/dashboard-old.blade.php          # ❌ DELETE
resources/views/admin/dashboard-simple.blade.php      # ❌ DELETE
resources/views/guru/materials/index_backup.blade.php # ❌ DELETE
```
**Alasan**: Sudah ada `dashboard.blade.php` yang aktif digunakan.

### **2. Redundant Controllers (Root Level)**
```bash
# Controllers yang duplikat dengan yang ada di folder role
app/Http/Controllers/GuruController.php               # ❌ DELETE
app/Http/Controllers/SiswaController.php              # ❌ DELETE
```
**Alasan**: Sudah ada controller spesifik di folder `Admin/`, `Guru/`, `Siswa/`

### **3. Unused Models (Based on New System)**
```bash
# Models yang tidak diperlukan dalam sistem baru
app/Models/CompetencyIndicator.php                    # ❌ DELETE
app/Models/Question.php                               # ❌ DELETE  
app/Models/QuestionOption.php                         # ❌ DELETE
app/Models/Exam.php                                   # ❌ DELETE
app/Models/Task.php                                   # ❌ DELETE
app/Models/TaskSubmission.php                         # ❌ DELETE
app/Models/PracticeModule.php                         # ❌ DELETE
app/Models/Result.php                                 # ❌ DELETE
```
**Alasan**: Sistem baru tidak menggunakan quiz engine yang kompleks, hanya upload/download soal.

### **4. Complex Controllers Not Needed**
```bash
# Controllers yang terlalu kompleks untuk sistem sederhana
app/Http/Controllers/Admin/BackupController.php       # ❌ DELETE
app/Http/Controllers/Admin/SettingController.php      # ❌ DELETE  
app/Http/Controllers/Admin/ReportController.php       # ❌ DELETE
app/Http/Controllers/Admin/PracticeScheduleController.php # ❌ DELETE
app/Http/Controllers/Guru/ScoringController.php       # ❌ DELETE
app/Http/Controllers/Guru/SubmissionController.php    # ❌ DELETE
```
**Alasan**: Fitur backup, complex reporting, dan scoring terpisah tidak diperlukan dalam sistem baru.

### **5. Unused API Controllers**
```bash
# API Controllers yang tidak diperlukan
app/Http/Controllers/Api/                             # ❌ DELETE ENTIRE FOLDER
routes/api.php                                        # ❌ DELETE
```
**Alasan**: Sistem baru fokus web interface, tidak perlu API terpisah.

### **6. Redundant Migrations**
```bash
# Migrations untuk fitur yang tidak diperlukan
database/migrations/*_create_questions_table.php            # ❌ DELETE
database/migrations/*_create_question_options_table.php     # ❌ DELETE
database/migrations/*_create_exams_table.php                # ❌ DELETE
database/migrations/*_create_feedback_table.php             # ❌ DELETE
database/migrations/*_create_results_table.php              # ❌ DELETE
database/migrations/*_create_practice_modules_table.php     # ❌ DELETE
database/migrations/*_create_practice_schedules_table.php   # ❌ DELETE
database/migrations/*_create_practice_schedule_participants_table.php # ❌ DELETE
database/migrations/*_fix_practice_schedule_participants_table.php    # ❌ DELETE
```
**Alasan**: Table-table ini tidak diperlukan dalam sistem yang disederhanakan.

### **7. Unused CSS/JS Files**
```bash
# Built files yang lama
public/build/                                         # ❌ DELETE ENTIRE FOLDER
public/css/admin.css                                  # ❌ DELETE (keep admin-new.css)
```
**Alasan**: File build lama dan CSS yang sudah diganti.

---

## ⚠️ **FILES TO RENAME/RESTRUCTURE**

### **1. Model Restructuring**
```bash
# Rename untuk consistency
app/Models/Guru.php        → app/Models/Teacher.php        # ✅ RENAME
app/Models/Student.php     → app/Models/Siswa.php          # ✅ RENAME (keep Indonesian)
```

### **2. Controller Cleanup**
```bash
# Merge similar functionality
app/Http/Controllers/Admin/AttendanceController.php       # ✅ SIMPLIFY
app/Http/Controllers/Guru/AttendanceController.php        # ✅ SIMPLIFY
app/Http/Controllers/Siswa/AttendanceController.php       # ✅ SIMPLIFY
```

---

## ✅ **FILES TO KEEP & OPTIMIZE**

### **1. Essential Controllers**
```bash
# Admin Core (4 main functions)
app/Http/Controllers/Admin/UserController.php             # ✅ KEEP - Kelola Guru/Siswa
app/Http/Controllers/Admin/DashboardController.php        # ✅ KEEP - Dashboard Admin

# Guru Core (5 main functions)  
app/Http/Controllers/Guru/MaterialController.php          # ✅ KEEP - Upload Materi
app/Http/Controllers/Guru/AssignmentController.php        # ✅ KEEP - Upload Soal/Quiz
app/Http/Controllers/Guru/PenilaianController.php         # ✅ KEEP - Penilaian Praktik
app/Http/Controllers/Guru/AttendanceController.php        # ✅ KEEP - Absensi Siswa
app/Http/Controllers/Guru/DashboardController.php         # ✅ KEEP - Dashboard Guru

# Siswa Core (4 main functions)
app/Http/Controllers/Siswa/MaterialController.php         # ✅ KEEP - Akses Materi
app/Http/Controllers/Siswa/AssignmentController.php       # ✅ KEEP - Lihat/Submit Quiz
app/Http/Controllers/Siswa/ScoreController.php            # ✅ KEEP - Laporan Nilai
app/Http/Controllers/Siswa/AttendanceController.php       # ✅ KEEP - Laporan Absensi
app/Http/Controllers/Siswa/DashboardController.php        # ✅ KEEP - Dashboard Siswa
```

### **2. Essential Models**
```bash
app/Models/User.php                    # ✅ KEEP - Core user model
app/Models/Kelas.php                   # ✅ KEEP - Class management
app/Models/Material.php                # ✅ KEEP - Learning materials
app/Models/Assignment.php              # ✅ KEEP - Quiz/assignments
app/Models/AssignmentSubmission.php    # ✅ KEEP - Student submissions
app/Models/Attendance.php              # ✅ KEEP - Attendance records
app/Models/PracticalScore.php          # ✅ KEEP - Practical assessment
app/Models/Notification.php            # ✅ KEEP - Auto notifications
app/Models/Setting.php                 # ✅ KEEP - Basic settings
```

### **3. Essential Views Structure**
```bash
resources/views/admin/dashboard.blade.php              # ✅ KEEP
resources/views/guru/dashboard.blade.php               # ✅ KEEP  
resources/views/siswa/dashboard.blade.php              # ✅ KEEP
resources/views/admin/users/                           # ✅ KEEP - User management
resources/views/guru/materials/                        # ✅ KEEP - Material upload
resources/views/guru/assignments/                      # ✅ KEEP - Assignment management
resources/views/siswa/materials/                       # ✅ KEEP - Material access
resources/views/siswa/assignments/                     # ✅ KEEP - Assignment access
```

---

## 🔄 **NEW FILES TO CREATE**

### **1. New Models for Revised System**
```bash
app/Models/Jurusan.php                                 # 🆕 CREATE - Program studi
app/Models/KriteriaPenilaian.php                       # 🆕 CREATE - Assessment criteria
app/Models/JadwalUjian.php                             # 🆕 CREATE - Exam schedule
app/Models/NilaiPraktik.php                            # 🆕 CREATE - Practical grades
app/Models/ScheduledNotification.php                   # 🆕 CREATE - Auto notifications
```

### **2. New Controllers**
```bash
app/Http/Controllers/Admin/JurusanController.php       # 🆕 CREATE
app/Http/Controllers/Admin/KelasController.php         # 🆕 CREATE  
app/Http/Controllers/Admin/KriteriaPenilaianController.php # 🆕 CREATE
app/Http/Controllers/Admin/JadwalUjianController.php   # 🆕 CREATE
```

### **3. New Migration Files**
```bash
database/migrations/create_jurusan_table.php           # 🆕 CREATE
database/migrations/create_kriteria_penilaian_table.php # 🆕 CREATE
database/migrations/create_jadwal_ujian_table.php      # 🆕 CREATE
database/migrations/create_nilai_praktik_table.php     # 🆕 CREATE
database/migrations/create_scheduled_notifications_table.php # 🆕 CREATE
```

---

## 📊 **CLEANUP IMPACT ANALYSIS**

### **Before Cleanup**
- **Total Files**: ~150+ files
- **Controllers**: 25 controllers
- **Models**: 26 models
- **Views**: 50+ view files
- **Migrations**: 40+ migrations

### **After Cleanup**  
- **Total Files**: ~100 files (-33%)
- **Controllers**: 15 controllers (-40%)
- **Models**: 15 models (-42%)
- **Views**: 35 view files (-30%)
- **Migrations**: 25 migrations (-37%)

### **Benefits**
- ✅ **Simplified Structure**: Easier navigation and maintenance
- ✅ **Reduced Complexity**: Focus on core functionality only
- ✅ **Better Performance**: Less file loading and processing
- ✅ **Cleaner Codebase**: Remove unused/redundant code
- ✅ **Easier Deployment**: Smaller file size and dependencies

---

## 🛠️ **CLEANUP EXECUTION PLAN**

### **Phase 1: Safe Deletions (Week 1)**
1. Delete backup and old dashboard files
2. Remove unused model files
3. Clean up redundant controllers
4. Remove API folder and routes

### **Phase 2: Database Cleanup (Week 2)**
1. Remove unused migrations
2. Create new migration files for revised system
3. Update existing models relationships

### **Phase 3: View Optimization (Week 3)**
1. Remove unused view files
2. Optimize remaining views for new system
3. Update layout files

### **Phase 4: Asset Cleanup (Week 4)**
1. Remove old CSS/JS builds
2. Optimize remaining assets
3. Update webpack/vite configuration

---

## ⚡ **IMMEDIATE ACTIONS**

### **High Priority Deletions** (Safe to delete immediately)
```bash
# These can be deleted right away
rm resources/views/admin/dashboard-old.blade.php
rm resources/views/admin/dashboard-simple.blade.php  
rm resources/views/guru/materials/index_backup.blade.php
rm app/Http/Controllers/GuruController.php
rm app/Http/Controllers/SiswaController.php
rm -rf public/build/
```

### **Medium Priority** (Need review first)
```bash
# Review before deleting
app/Models/Question.php
app/Models/QuestionOption.php
app/Models/Exam.php
app/Http/Controllers/Api/
```

### **Low Priority** (Clean up after new system is implemented)
```bash
# Clean up after new features are implemented
database/migrations/*_create_questions_table.php
database/migrations/*_create_exams_table.php
```

---

## 📋 **CLEANUP CHECKLIST**

### **Files Audit**
- [x] Identified redundant dashboard files
- [x] Found duplicate controllers  
- [x] Listed unused models
- [x] Reviewed API necessity
- [x] Checked migration relevance

### **Structure Optimization**
- [ ] Execute safe file deletions
- [ ] Rename inconsistent files
- [ ] Create new required files
- [ ] Update routes accordingly
- [ ] Test system functionality

### **Performance Impact**
- [ ] Measure before/after metrics
- [ ] Test application loading time
- [ ] Verify no broken references
- [ ] Update documentation

---

**Cleanup Status**: Ready for Execution ✅  
**Estimated Time Savings**: 30-40% reduction in file complexity  
**Risk Level**: Low (most deletions are safe redundant files)  

**Next Action**: Begin with Phase 1 safe deletions immediately.