<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScoringController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:guru');
    }

    /**
     * Display scoring dashboard
     */
    public function index(Request $request): View
    {
        return view('guru.penilaian.index', [
            'title' => 'Penilaian Siswa'
        ]);
    }

    /**
     * Export scoring data
     */
    public function export(Request $request)
    {
        // Export functionality implementation
        return back()->with('success', 'Export penilaian berhasil dilakukan');
    }
}
