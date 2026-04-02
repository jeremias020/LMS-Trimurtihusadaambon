<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Kelas;
use App\Models\User;
use App\Models\ClassSubject;
use App\Models\Assignment;
use App\Models\Material;
use App\Models\Practical;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PelajaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display daftar pelajaran untuk siswa
     */
    public function index(Request $request): View
    {
        $siswa = Auth::user();
        $siswaId = $siswa->id;
        
        // Get kelas siswa (jika ada)
        $kelasId = null;
        $kelas = null;
        
        // Coba dapatkan kelas dari berbagai cara
        try {
            // Cek apakah ada field kelas_id di user
            if (isset($siswa->kelas_id)) {
                $kelasId = $siswa->kelas_id;
            }
            
            if ($kelasId) {
                $kelas = Kelas::with(['jurusan'])->find($kelasId);
            }
        } catch (\Exception $e) {
            // Abaikan error jika field tidak ada
        }
        
        // Get mata pelajaran yang tersedia
        $subjectsQuery = Subject::with(['jurusan']);
        
        // Jika ada kelas_id, filter berdasarkan class_subjects
        if ($kelasId) {
            try {
                $subjectIds = ClassSubject::where('kelas_id', $kelasId)
                    ->pluck('subject_id')
                    ->toArray();
                
                if (!empty($subjectIds)) {
                    $subjectsQuery->whereIn('id', $subjectIds);
                }
            } catch (\Exception $e) {
                // Abaikan error jika ClassSubject tidak ada
            }
        }
        
        // Filter hanya mata pelajaran aktif
        $subjects = $subjectsQuery->get();
        
        // Enrich subjects dengan data tambahan
        foreach ($subjects as $subject) {
            try {
                // Hitung jumlah materi
                $materialCount = Material::where('subject_id', $subject->id)
                    ->where(function($query) use ($kelasId) {
                        if ($kelasId) {
                            $query->where('kelas_id', $kelasId);
                        }
                        $query->orWhereNull('kelas_id');
                    })
                    ->whereNotNull('published_at')
                    ->count();
                
                // Hitung jumlah tugas
                $assignmentCount = Assignment::where('subject_id', $subject->id)
                    ->where(function($query) use ($kelasId) {
                        if ($kelasId) {
                            $query->where('kelas_id', $kelasId);
                        }
                        $query->orWhereNull('kelas_id');
                    })
                    ->where('is_published', true)
                    ->count();
                
                // Hitung jumlah praktikum
                $practicalCount = Practical::where('subject_id', $subject->id)
                    ->where(function($query) use ($kelasId) {
                        if ($kelasId) {
                            $query->where('kelas_id', $kelasId);
                        }
                        $query->orWhereNull('kelas_id');
                    })
                    ->whereNotNull('published_at')
                    ->count();
                
                $subject->material_count = $materialCount;
                $subject->assignment_count = $assignmentCount;
                $subject->practical_count = $practicalCount;
                $subject->total_activities = $materialCount + $assignmentCount + $practicalCount;
            } catch (\Exception $e) {
                // Set default jika error
                $subject->material_count = 0;
                $subject->assignment_count = 0;
                $subject->practical_count = 0;
                $subject->total_activities = 0;
            }
        }
        
        // Get data siswa lengkap
        $siswaData = [
            'id' => $siswa->id,
            'name' => $siswa->name,
            'email' => $siswa->email,
            'nis_nip' => $siswa->nis_nip ?? '',
            'kelas' => $kelas ? $kelas->name : 'Belum ada kelas',
            'kelas_lengkap' => $kelas ? $kelas->name : 'Belum ada kelas',
            'jurusan' => $kelas && $kelas->jurusan ? $kelas->jurusan->name : 'Belum ada jurusan',
            'wali_kelas' => 'Belum ada wali kelas', // Tidak ada relationship guru
        ];
        
        return view('siswa.pelajaran.index', compact(
            'subjects',
            'siswaData',
            'kelas'
        ));
    }
    
    /**
     * Show detail mata pelajaran
     */
    public function show($id): View
    {
        $siswa = Auth::user();
        $kelasId = null;
        
        // Coba dapatkan kelas_id
        try {
            if (isset($siswa->kelas_id)) {
                $kelasId = $siswa->kelas_id;
            }
        } catch (\Exception $e) {
            // Abaikan error
        }
        
        $subject = Subject::with([
            'jurusan'
        ])->findOrFail($id);
        
        // Load materials, assignments, dan practicals secara manual
        try {
            $materials = Material::where('subject_id', $subject->id)
                ->where(function($query) use ($kelasId) {
                    if ($kelasId) {
                        $query->where('kelas_id', $kelasId);
                    }
                    $query->orWhereNull('kelas_id');
                })
                ->whereNotNull('published_at')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $assignments = Assignment::where('subject_id', $subject->id)
                ->where(function($query) use ($kelasId) {
                    if ($kelasId) {
                        $query->where('kelas_id', $kelasId);
                    }
                    $query->orWhereNull('kelas_id');
                })
                ->where('is_published', true)
                ->orderBy('created_at', 'desc')
                ->get();
            
            $practicals = Practical::where('subject_id', $subject->id)
                ->where(function($query) use ($kelasId) {
                    if ($kelasId) {
                        $query->where('kelas_id', $kelasId);
                    }
                    $query->orWhereNull('kelas_id');
                })
                ->whereNotNull('published_at')
                ->orderBy('created_at', 'desc')
                ->get();
                
        } catch (\Exception $e) {
            $materials = collect();
            $assignments = collect();
            $practicals = collect();
        }
        
        return view('siswa.pelajaran.show', compact(
            'subject',
            'materials',
            'assignments',
            'practicals',
            'kelasId'
        ));
    }
}
