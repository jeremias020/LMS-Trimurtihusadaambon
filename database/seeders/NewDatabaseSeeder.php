<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NewDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with new schema.
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing data for new tables
        $this->truncateNewTables();
        
        // Seed data
        $this->seedUsers();
        $this->seedMajors();
        $this->seedClasses();
        $this->seedClassStudents();
        $this->seedSubjects();
        $this->seedClassSubjects();
        $this->seedAssessmentCriteria();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('New database schema seeded successfully!');
    }
    
    private function truncateNewTables()
    {
        $tables = [
            'assignment_submissions', 'assignments', 'materials', 'attendances',
            'practical_assessments', 'assessment_criteria', 'class_subjects',
            'class_students', 'subjects', 'classes', 'majors', 'users'
        ];
        
        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
            }
        }
    }
    
    private function seedUsers()
    {
        // Admin
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@lms-trimurti.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nis_nip' => 'ADMIN001',
            'phone' => '08123456789',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Guru
        $teachers = [
            ['name' => 'Dr. Siti Nurhaliza', 'email' => 'siti@lms-trimurti.sch.id', 'nis_nip' => 'GURU001', 'phone' => '08123456780'],
            ['name' => 'Dr. Budi Santoso', 'email' => 'budi@lms-trimurti.sch.id', 'nis_nip' => 'GURU002', 'phone' => '08123456781'],
            ['name' => 'Dra. Ani Wijaya', 'email' => 'ani@lms-trimurti.sch.id', 'nis_nip' => 'GURU003', 'phone' => '08123456782'],
        ];
        
        foreach ($teachers as $teacher) {
            DB::table('users')->insert([
                'name' => $teacher['name'],
                'email' => $teacher['email'],
                'password' => Hash::make('password'),
                'role' => 'guru',
                'nis_nip' => $teacher['nis_nip'],
                'phone' => $teacher['phone'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Siswa
        $students = [
            ['name' => 'Agus Setiawan', 'email' => 'agus.setiawan@lms-trimurti.sch.id', 'nis_nip' => 'SISWA001', 'phone' => '08123456790'],
            ['name' => 'Siti Aminah', 'email' => 'siti.aminah@lms-trimurti.sch.id', 'nis_nip' => 'SISWA002', 'phone' => '08123456791'],
            ['name' => 'Budi Pratama', 'email' => 'budi.pratama@lms-trimurti.sch.id', 'nis_nip' => 'SISWA003', 'phone' => '08123456792'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi.lestari@lms-trimurti.sch.id', 'nis_nip' => 'SISWA004', 'phone' => '08123456793'],
            ['name' => 'Rudi Hermawan', 'email' => 'rudi.hermawan@lms-trimurti.sch.id', 'nis_nip' => 'SISWA005', 'phone' => '08123456794'],
        ];
        
        foreach ($students as $student) {
            DB::table('users')->insert([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'nis_nip' => $student['nis_nip'],
                'phone' => $student['phone'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    
    private function seedMajors()
    {
        $majors = [
            ['name' => 'Keperawatan', 'code' => 'KEP', 'description' => 'Program keahlian Keperawatan dengan fokus pada perawatan pasien'],
            ['name' => 'Farmasi', 'code' => 'FAR', 'description' => 'Program keahlian Farmasi dengan fokus pada pengelolaan obat'],
            ['name' => 'Analis Kesehatan', 'code' => 'ANK', 'description' => 'Program keahlian Analis Kesehatan dengan fokus pada laboratorium'],
        ];
        
        foreach ($majors as $major) {
            DB::table('majors')->insert([
                'name' => $major['name'],
                'code' => $major['code'],
                'description' => $major['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    
    private function seedClasses()
    {
        $classes = [
            ['name' => 'X Keperawatan 1', 'major_id' => 1, 'academic_year' => '2024/2025'],
            ['name' => 'X Keperawatan 2', 'major_id' => 1, 'academic_year' => '2024/2025'],
            ['name' => 'XI Keperawatan 1', 'major_id' => 1, 'academic_year' => '2024/2025'],
            ['name' => 'X Farmasi 1', 'major_id' => 2, 'academic_year' => '2024/2025'],
            ['name' => 'X Analis Kesehatan 1', 'major_id' => 3, 'academic_year' => '2024/2025'],
        ];
        
        foreach ($classes as $class) {
            DB::table('classes')->insert([
                'name' => $class['name'],
                'major_id' => $class['major_id'],
                'academic_year' => $class['academic_year'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    
    private function seedClassStudents()
    {
        // Assign students to classes
        $classStudents = [
            ['class_id' => 1, 'student_id' => 4], // Agus Setiawan
            ['class_id' => 1, 'student_id' => 5], // Siti Aminah
            ['class_id' => 1, 'student_id' => 6], // Budi Pratama
            ['class_id' => 2, 'student_id' => 7], // Dewi Lestari
            ['class_id' => 2, 'student_id' => 8], // Rudi Hermawan
        ];
        
        foreach ($classStudents as $classStudent) {
            DB::table('class_students')->insert([
                'class_id' => $classStudent['class_id'],
                'student_id' => $classStudent['student_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    
    private function seedSubjects()
    {
        $subjects = [
            // Keperawatan
            ['name' => 'Anatomi Fisiologi', 'code' => 'AF', 'major_id' => 1],
            ['name' => 'Farmakologi', 'code' => 'FA', 'major_id' => 1],
            ['name' => 'Keperawatan Dasar', 'code' => 'KD', 'major_id' => 1],
            ['name' => 'Keperawatan Medikal Bedah', 'code' => 'KMB', 'major_id' => 1],
            ['name' => 'Keperawatan Anak', 'code' => 'KA', 'major_id' => 1],
            
            // Farmasi
            ['name' => 'Kimia Farmasi', 'code' => 'KF', 'major_id' => 2],
            ['name' => 'Farmakologi', 'code' => 'FAR', 'major_id' => 2],
            ['name' => 'Teknik Farmasi', 'code' => 'TF', 'major_id' => 2],
            
            // Analis Kesehatan
            ['name' => 'Kimia Klinik', 'code' => 'KK', 'major_id' => 3],
            ['name' => 'Hematologi', 'code' => 'HEM', 'major_id' => 3],
        ];
        
        foreach ($subjects as $subject) {
            DB::table('subjects')->insert([
                'name' => $subject['name'],
                'code' => $subject['code'],
                'major_id' => $subject['major_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    
    private function seedClassSubjects()
    {
        $classSubjects = [
            // X Keperawatan 1
            ['class_id' => 1, 'subject_id' => 1, 'teacher_id' => 2, 'day' => 'Senin', 'start_time' => '07:00:00', 'end_time' => '08:30:00', 'room' => 'Ruang 1'],
            ['class_id' => 1, 'subject_id' => 2, 'teacher_id' => 3, 'day' => 'Selasa', 'start_time' => '07:00:00', 'end_time' => '08:30:00', 'room' => 'Lab 1'],
            ['class_id' => 1, 'subject_id' => 3, 'teacher_id' => 4, 'day' => 'Rabu', 'start_time' => '07:00:00', 'end_time' => '08:30:00', 'room' => 'Ruang 2'],
            
            // X Keperawatan 2
            ['class_id' => 2, 'subject_id' => 1, 'teacher_id' => 2, 'day' => 'Kamis', 'start_time' => '07:00:00', 'end_time' => '08:30:00', 'room' => 'Ruang 1'],
            ['class_id' => 2, 'subject_id' => 4, 'teacher_id' => 3, 'day' => 'Jumat', 'start_time' => '07:00:00', 'end_time' => '08:30:00', 'room' => 'Lab Keperawatan'],
        ];
        
        foreach ($classSubjects as $classSubject) {
            DB::table('class_subjects')->insert([
                'class_id' => $classSubject['class_id'],
                'subject_id' => $classSubject['subject_id'],
                'teacher_id' => $classSubject['teacher_id'],
                'day' => $classSubject['day'],
                'start_time' => $classSubject['start_time'],
                'end_time' => $classSubject['end_time'],
                'room' => $classSubject['room'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    
    private function seedAssessmentCriteria()
    {
        $criteria = [
            // Anatomi Fisiologi
            ['subject_id' => 1, 'code' => 'KD 3.1', 'name' => 'Memahami sistem peredaran darah', 'type' => 'knowledge'],
            ['subject_id' => 1, 'code' => 'KD 3.2', 'name' => 'Memahami sistem pernapasan', 'type' => 'knowledge'],
            ['subject_id' => 1, 'code' => 'KD 4.1', 'name' => 'Mengidentifikasi organ tubuh', 'type' => 'skill'],
            
            // Farmakologi
            ['subject_id' => 2, 'code' => 'KD 3.1', 'name' => 'Memahami klasifikasi obat', 'type' => 'knowledge'],
            ['subject_id' => 2, 'code' => 'KD 4.1', 'name' => 'Menghitung dosis obat', 'type' => 'skill'],
            
            // Keperawatan Dasar
            ['subject_id' => 3, 'code' => 'KD 3.1', 'name' => 'Memahami konsep keperawatan', 'type' => 'knowledge'],
            ['subject_id' => 3, 'code' => 'KD 4.1', 'name' => 'Melakukan pengukuran vital sign', 'type' => 'skill'],
        ];
        
        foreach ($criteria as $criterion) {
            DB::table('assessment_criteria')->insert([
                'subject_id' => $criterion['subject_id'],
                'code' => $criterion['code'],
                'name' => $criterion['name'],
                'type' => $criterion['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
