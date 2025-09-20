# 🛠️ Technical Specifications - LMS SMK Kesehatan

## 📊 **DATABASE SCHEMA REVISI**

### **1. Core Tables**

```sql
-- Users Management
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'guru', 'siswa') NOT NULL,
    kelas_id BIGINT NULL,
    jurusan_id BIGINT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id),
    FOREIGN KEY (jurusan_id) REFERENCES jurusan(id)
);

-- Academic Structure
CREATE TABLE jurusan (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    mata_pelajaran JSON, -- ["Anatomi", "Fisiologi", "Farmakologi"]
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE kelas (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL, -- "X Keperawatan A"
    tingkat ENUM('X', 'XI', 'XII') NOT NULL,
    jurusan_id BIGINT NOT NULL,
    kapasitas INT DEFAULT 30,
    wali_kelas_id BIGINT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (jurusan_id) REFERENCES jurusan(id),
    FOREIGN KEY (wali_kelas_id) REFERENCES users(id)
);

-- Assessment Criteria
CREATE TABLE kriteria_penilaian (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    kategori ENUM('persiapan', 'pelaksanaan', 'hasil', 'sikap') NOT NULL,
    bobot DECIMAL(3,2) NOT NULL, -- 0.25 = 25%
    deskripsi TEXT,
    sop_checklist JSON, -- Array of checklist items
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Exam Scheduling
CREATE TABLE jadwal_ujian (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    tanggal DATE NOT NULL,
    waktu_mulai TIME NOT NULL,
    waktu_selesai TIME NOT NULL,
    mata_pelajaran VARCHAR(255) NOT NULL,
    kelas_id BIGINT NOT NULL,
    jenis_ujian ENUM('quiz', 'uts', 'uas', 'praktik') NOT NULL,
    pengawas_id BIGINT NULL,
    ruangan VARCHAR(100),
    status ENUM('scheduled', 'ongoing', 'completed', 'cancelled') DEFAULT 'scheduled',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id),
    FOREIGN KEY (pengawas_id) REFERENCES users(id)
);
```

### **2. Content & Assessment Tables**

```sql
-- Learning Materials
CREATE TABLE materials (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(500) NOT NULL,
    file_type VARCHAR(50), -- PDF, PPT, MP4, etc
    file_size BIGINT, -- in bytes
    mata_pelajaran VARCHAR(255) NOT NULL,
    kelas_id BIGINT NOT NULL,
    uploaded_by BIGINT NOT NULL, -- guru_id
    visibility ENUM('public', 'private') DEFAULT 'public',
    download_count INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id),
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

-- Assignments & Quizzes
CREATE TABLE assignments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(500) NOT NULL,
    mata_pelajaran VARCHAR(255) NOT NULL,
    kelas_id BIGINT NOT NULL,
    created_by BIGINT NOT NULL, -- guru_id
    jenis ENUM('quiz', 'tugas', 'uts', 'uas') NOT NULL,
    deadline DATETIME NOT NULL,
    max_score DECIMAL(5,2) DEFAULT 100.00,
    status ENUM('draft', 'published', 'closed') DEFAULT 'draft',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Student Submissions
CREATE TABLE submissions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    assignment_id BIGINT NOT NULL,
    siswa_id BIGINT NOT NULL,
    file_path VARCHAR(500),
    submitted_at TIMESTAMP NOT NULL,
    score DECIMAL(5,2) NULL,
    feedback TEXT,
    status ENUM('submitted', 'graded', 'late') DEFAULT 'submitted',
    graded_by BIGINT NULL,
    graded_at TIMESTAMP NULL,
    FOREIGN KEY (assignment_id) REFERENCES assignments(id),
    FOREIGN KEY (siswa_id) REFERENCES users(id),
    FOREIGN KEY (graded_by) REFERENCES users(id),
    UNIQUE KEY unique_submission (assignment_id, siswa_id)
);
```

### **3. Practical Assessment Tables**

```sql
-- Practical Assessments
CREATE TABLE nilai_praktik (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    siswa_id BIGINT NOT NULL,
    guru_id BIGINT NOT NULL,
    mata_praktik VARCHAR(255) NOT NULL,
    tanggal_praktik DATE NOT NULL,
    total_nilai DECIMAL(5,2) NOT NULL,
    grade ENUM('A', 'B', 'C', 'D', 'E') NOT NULL,
    feedback_otomatis TEXT,
    status ENUM('draft', 'final') DEFAULT 'draft',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES users(id),
    FOREIGN KEY (guru_id) REFERENCES users(id)
);

-- Detailed Scoring per Criteria
CREATE TABLE detail_penilaian (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nilai_praktik_id BIGINT NOT NULL,
    kriteria_id BIGINT NOT NULL,
    skor INT NOT NULL, -- 1-4 scale
    catatan TEXT,
    created_at TIMESTAMP,
    FOREIGN KEY (nilai_praktik_id) REFERENCES nilai_praktik(id),
    FOREIGN KEY (kriteria_id) REFERENCES kriteria_penilaian(id)
);

-- Attendance Records
CREATE TABLE absensi (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    siswa_id BIGINT NOT NULL,
    mata_pelajaran VARCHAR(255) NOT NULL,
    tanggal DATE NOT NULL,
    status ENUM('hadir', 'izin', 'sakit', 'alpha') NOT NULL,
    keterangan TEXT NULL,
    input_by BIGINT NOT NULL, -- guru_id
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES users(id),
    FOREIGN KEY (input_by) REFERENCES users(id),
    UNIQUE KEY unique_attendance (siswa_id, mata_pelajaran, tanggal)
);
```

### **4. Notification Tables**

```sql
-- System Notifications
CREATE TABLE notifications (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    type VARCHAR(100) NOT NULL, -- 'exam_reminder', 'grade_feedback', etc
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    data JSON, -- Additional data
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_user_unread (user_id, read_at)
);

-- Scheduled Notifications for Exams
CREATE TABLE scheduled_notifications (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    jadwal_ujian_id BIGINT NOT NULL,
    notification_type ENUM('h7', 'h3', 'h1', 'h0') NOT NULL,
    scheduled_at DATETIME NOT NULL,
    sent_at TIMESTAMP NULL,
    status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP,
    FOREIGN KEY (jadwal_ujian_id) REFERENCES jadwal_ujian(id),
    INDEX idx_scheduled (scheduled_at, status)
);
```

---

## 🔄 **AUTO-NOTIFICATION SYSTEM**

### **Laravel Job Classes**

```php
// app/Jobs/SendExamNotification.php
class SendExamNotification implements ShouldQueue
{
    public function handle()
    {
        $today = now();
        
        // Check for notifications to send
        $notifications = ScheduledNotification::where('scheduled_at', '<=', $today)
            ->where('status', 'pending')
            ->with('jadwalUjian.kelas')
            ->get();
            
        foreach ($notifications as $notification) {
            $this->sendNotificationToUsers($notification);
            $notification->update(['status' => 'sent', 'sent_at' => now()]);
        }
    }
    
    private function sendNotificationToUsers($notification)
    {
        $jadwal = $notification->jadwalUjian;
        $users = User::where('kelas_id', $jadwal->kelas_id)
            ->orWhere('id', $jadwal->pengawas_id)
            ->get();
            
        foreach ($users as $user) {
            $user->notifications()->create([
                'type' => 'exam_reminder',
                'title' => "Reminder Ujian {$jadwal->mata_pelajaran}",
                'message' => $this->getNotificationMessage($notification, $jadwal),
                'data' => json_encode(['jadwal_ujian_id' => $jadwal->id])
            ]);
        }
    }
}
```

### **Auto-Grade Feedback System**

```php
// app/Services/GradingService.php
class GradingService
{
    public function submitPracticalGrade($nilaiPraktikId)
    {
        $nilaiPraktik = NilaiPraktik::with('detailPenilaian', 'siswa')->find($nilaiPraktikId);
        
        // Calculate total score
        $totalScore = $this->calculateTotalScore($nilaiPraktik);
        
        // Generate feedback
        $feedback = $this->generateAutoFeedback($nilaiPraktik);
        
        // Update grade
        $nilaiPraktik->update([
            'total_nilai' => $totalScore,
            'grade' => $this->determineGrade($totalScore),
            'feedback_otomatis' => $feedback,
            'status' => 'final'
        ]);
        
        // Send notification to student
        $this->sendGradeNotification($nilaiPraktik);
        
        return $nilaiPraktik;
    }
    
    private function generateAutoFeedback($nilaiPraktik)
    {
        $feedback = "🎯 Nilai Praktik Anda: {$nilaiPraktik->total_nilai}/100\n📊 Detail:\n";
        
        foreach ($nilaiPraktik->detailPenilaian as $detail) {
            $kriteria = $detail->kriteria;
            $level = $this->getScoreLevel($detail->skor);
            $feedback .= "- {$kriteria->nama}: {$detail->skor}/4 ({$level})\n";
        }
        
        $feedback .= "\n💬 Saran: " . $this->getImprovementSuggestion($nilaiPraktik);
        
        return $feedback;
    }
}
```

---

## 📱 **FRONTEND COMPONENTS**

### **Admin Dashboard Components**

```php
// resources/views/admin/dashboard-revised.blade.php
<div class="admin-dashboard">
    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <h3>{{ $totalGuru }}</h3>
                <p>Total Guru</p>
                <a href="{{ route('admin.guru.index') }}" class="btn btn-sm btn-primary">Kelola</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h3>{{ $totalSiswa }}</h3>
                <p>Total Siswa</p>
                <a href="{{ route('admin.siswa.index') }}" class="btn btn-sm btn-primary">Kelola</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h3>{{ $totalKelas }}</h3>
                <p>Total Kelas</p>
                <a href="{{ route('admin.kelas.index') }}" class="btn btn-sm btn-primary">Kelola</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h3>{{ $upcomingExams }}</h3>
                <p>Ujian Mendatang</p>
                <a href="{{ route('admin.jadwal-ujian.index') }}" class="btn btn-sm btn-primary">Kelola</a>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Data Master</div>
                <div class="card-body">
                    <a href="{{ route('admin.guru.create') }}" class="btn btn-outline-primary mb-2 d-block">
                        <i class="fas fa-plus"></i> Tambah Guru
                    </a>
                    <a href="{{ route('admin.siswa.create') }}" class="btn btn-outline-primary mb-2 d-block">
                        <i class="fas fa-plus"></i> Tambah Siswa  
                    </a>
                    <a href="{{ route('admin.kelas.create') }}" class="btn btn-outline-primary mb-2 d-block">
                        <i class="fas fa-plus"></i> Tambah Kelas
                    </a>
                    <a href="{{ route('admin.jurusan.create') }}" class="btn btn-outline-primary d-block">
                        <i class="fas fa-plus"></i> Tambah Jurusan
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Jadwal & Penilaian</div>
                <div class="card-body">
                    <a href="{{ route('admin.jadwal-ujian.create') }}" class="btn btn-outline-success mb-2 d-block">
                        <i class="fas fa-calendar-plus"></i> Tambah Jadwal Ujian
                    </a>
                    <a href="{{ route('admin.kriteria-penilaian.index') }}" class="btn btn-outline-success mb-2 d-block">
                        <i class="fas fa-clipboard-check"></i> Kelola Kriteria Penilaian
                    </a>
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-success d-block">
                        <i class="fas fa-bell"></i> Monitor Notifikasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
```

### **Guru Assessment Form**

```php
// resources/views/guru/penilaian-praktik.blade.php
<form id="practicalAssessmentForm" action="{{ route('guru.penilaian-praktik.store') }}" method="POST">
    @csrf
    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
    <input type="hidden" name="mata_praktik" value="{{ $mataPraktik }}">
    
    <div class="assessment-form">
        <h4>Penilaian Praktik: {{ $mataPraktik }}</h4>
        <p><strong>Siswa:</strong> {{ $siswa->name }}</p>
        
        @foreach($kriteriaList as $kriteria)
        <div class="criteria-section mb-4">
            <h5>{{ $kriteria->nama }} ({{ $kriteria->bobot * 100 }}%)</h5>
            <p class="text-muted">{{ $kriteria->deskripsi }}</p>
            
            <div class="scoring-options">
                @for($i = 1; $i <= 4; $i++)
                <label class="score-option">
                    <input type="radio" name="kriteria[{{ $kriteria->id }}]" value="{{ $i }}" required>
                    <span class="score-label">{{ $i }} - {{ $scoreLabels[$i] }}</span>
                </label>
                @endfor
            </div>
            
            <textarea name="catatan[{{ $kriteria->id }}]" class="form-control mt-2" 
                placeholder="Catatan untuk {{ $kriteria->nama }}"></textarea>
        </div>
        @endforeach
        
        <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Submit penilaian? Nilai akan langsung dikirim ke siswa.')">
            <i class="fas fa-paper-plane"></i> Submit Penilaian
        </button>
    </div>
</form>

<script>
document.getElementById('practicalAssessmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    submitBtn.disabled = true;
    
    // Submit via AJAX
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Penilaian berhasil disubmit! Nilai dan feedback otomatis telah dikirim ke siswa.');
            window.location.href = '{{ route("guru.dashboard") }}';
        } else {
            alert('❌ Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('❌ Terjadi kesalahan sistem');
        console.error(error);
    })
    .finally(() => {
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Penilaian';
        submitBtn.disabled = false;
    });
});
</script>
```

---

## 🔐 **SECURITY & MIDDLEWARE**

```php
// app/Http/Middleware/CheckRole.php
class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            abort(403, 'Unauthorized access');
        }
        
        // Additional role-specific checks
        if ($role === 'guru' && auth()->user()->status !== 'active') {
            abort(403, 'Account not active');
        }
        
        return $next($request);
    }
}

// Route protection
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin routes only
});

Route::middleware(['auth', 'role:guru'])->group(function () {
    // Guru routes only  
});

Route::middleware(['auth', 'role:siswa'])->group(function () {
    // Siswa routes only
});
```

---

## 📊 **PERFORMANCE & OPTIMIZATION**

### **Caching Strategy**
```php
// Cache frequently accessed data
Cache::remember('jurusan_list', 3600, function () {
    return Jurusan::all();
});

Cache::remember('upcoming_exams', 1800, function () {
    return JadwalUjian::where('tanggal', '>=', now())->get();
});
```

### **Queue Configuration**
```php
// config/queue.php - Setup for notification processing
'connections' => [
    'database' => [
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
    ],
]
```

---

**Implementation Ready** ✅  
**Next Step**: Database migration dan controller implementation  
**Timeline**: 8 weeks development cycle