<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'siswa']);
    }

    /**
     * Display student reports and grades
     */
    public function index(): View
    {
        $siswa = Auth::user();
        
        // Get student data
        $student = \App\Models\Student::find($siswa->id);
        
        // Get assignment submissions with grades
        $assignmentSubmissions = \App\Models\AssignmentSubmission::where('siswa_id', $siswa->id)
            ->with('assignment.subject')
            ->get();
            
        // Get practical scores
        $practicalScores = \App\Models\PracticalScore::where('siswa_id', $siswa->id)
            ->with('practical.subject')
            ->get();
            
        // Get attendance records
        $attendances = \App\Models\Attendance::where('siswa_id', $siswa->id)
            ->orderBy('tanggal', 'desc')
            ->get();
            
        // Calculate statistics
        $totalAssignments = $assignmentSubmissions->count();
        $gradedAssignments = $assignmentSubmissions->whereNotNull('score')->count();
        $averageScore = $assignmentSubmissions->whereNotNull('score')->avg('score');
        
        $totalPracticals = $practicalScores->count();
        $gradedPracticals = $practicalScores->whereNotNull('score')->count();
        $averagePracticalScore = $practicalScores->whereNotNull('score')->avg('score');
        
        $totalAttendances = $attendances->count();
        $presentAttendances = $attendances->where('status', 'hadir')->count();
        
        return view('siswa.reports.index', compact(
            'student',
            'assignmentSubmissions',
            'practicalScores',
            'attendances',
            'totalAssignments',
            'gradedAssignments',
            'averageScore',
            'totalPracticals',
            'gradedPracticals',
            'averagePracticalScore',
            'totalAttendances',
            'presentAttendances'
        ));
    }
}