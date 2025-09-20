<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Practical;
use App\Models\PracticalScore;
use App\Models\Criteria;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PracticalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:guru');
    }

    /**
     * Display a listing of practicals.
     */
    public function index(): View
    {
        $status = request('status', 'all');
        $guruId = Auth::id();

        $query = Practical::withCount('scores')
            ->with('subject')
            ->where('guru_id', $guruId);

        if ($status !== 'all') {
            match($status) {
                'published' => $query->where('is_published', true),
                'draft' => $query->where('is_published', false),
                'upcoming' => $query->where('tanggal', '>=', Carbon::today()),
                'completed' => $query->where('tanggal', '<', Carbon::today()),
            };
        }

        $practicals = $query->latest()->paginate(12);

        // Perbaiki stats query untuk performa yang lebih baik
        $stats = [
            'total' => Practical::where('guru_id', $guruId)->count(),
            'published' => Practical::where('guru_id', $guruId)->where('is_published', true)->count(),
            'upcoming' => Practical::where('guru_id', $guruId)->where('tanggal', '>=', Carbon::today())->count(),
        ];

        $subjects = Subject::where('is_active', true)->get();
        
        return view('guru.praktikum.index', compact('practicals', 'stats', 'status', 'subjects'));
    }

    /**
     * Show the form for creating a new practical.
     */
    public function create(): View
    {
        $subjects = Subject::where('is_active', true)->get();
        $criterias = Criteria::where('is_active', true)->get();
        $classes = \App\Models\Kelas::where('status', 'active')->get();

        $skillLevels = [
            'Pemula' => 'Pemula',
            'Menengah' => 'Menengah',
            'Mahir' => 'Mahir'
        ];

        return view('guru.praktikum.create', compact('subjects', 'criterias', 'classes', 'skillLevels'));
    }

    /**
     * Store a newly created practical.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'lokasi' => 'required|string|max:255',
            'durasi' => 'required|integer|min:1|max:480',
            'skill_level' => 'required|in:Pemula,Menengah,Mahir',
            'tools' => 'required|array|min:1',
            'tools.*' => 'string|max:100',
            'bahan' => 'required|array|min:1',
            'bahan.*' => 'string|max:100',
            'instruksi' => 'required|array|min:1',
            'instruksi.*' => 'string|max:500',
            'keselamatan' => 'required|array|min:1',
            'keselamatan.*' => 'string|max:500',
            'kelas_id' => 'required|exists:kelas,id',
            'max_score' => 'required|integer|min:1|max:1000',
        ], [
            'tanggal.after_or_equal' => 'Tanggal tidak boleh di masa lalu',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai',
            'tools.required' => 'Minimal 1 alat harus dimasukkan',
            'bahan.required' => 'Minimal 1 bahan harus dimasukkan',
            'instruksi.required' => 'Minimal 1 instruksi harus dimasukkan',
            'keselamatan.required' => 'Minimal 1 prosedur keselamatan harus dimasukkan',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form');
        }

        try {
            $practical = new Practical();
            $practical->guru_id = Auth::id();
            $practical->subject_id = $request->subject_id;
            $practical->judul = $request->judul;
            $practical->deskripsi = $request->deskripsi;
            $practical->tanggal = $request->tanggal;
            $practical->waktu_mulai = $request->waktu_mulai;
            $practical->waktu_selesai = $request->waktu_selesai;
            $practical->lokasi = $request->lokasi;
            $practical->durasi = $request->durasi;
            $practical->skill_level = $request->skill_level;
            $practical->tools = json_encode($request->tools);
            $practical->bahan = json_encode($request->bahan);
            $practical->instruksi = json_encode($request->instruksi);
            $practical->keselamatan = json_encode($request->keselamatan);
            $practical->kelas_id = $request->kelas_id;
            $practical->max_score = $request->max_score;
            $practical->is_published = $request->has('is_published');

            $practical->save();

            Log::info('Practical created', [
                'practical_id' => $practical->id,
                'guru_id' => Auth::id(),
                'judul' => $practical->judul,
                'ip' => $request->ip()
            ]);

            return redirect()->route('guru.praktikum.index')
                ->with('success', 'Praktikum berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Practical creation failed: ' . $e->getMessage(), [
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified practical.
     */
    public function show(Practical $praktikum): View
    {
        // ✅ Security: Double-check ownership
        if ($praktikum->guru_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('view', $praktikum);

        $scores = PracticalScore::with(['siswa', 'criteria'])
            ->where('practical_id', $praktikum->id)
            ->latest()
            ->paginate(15);

        // ✅ Perbaikan: Hindari division by zero
        $totalStudents = User::where('kelas_id', $praktikum->kelas_id)
            ->where('role', 'siswa')
            ->count();

        $completed = PracticalScore::where('practical_id', $praktikum->id)
            ->distinct('siswa_id')
            ->count('siswa_id');

        $stats = [
            'average_score' => PracticalScore::where('practical_id', $praktikum->id)->avg('score') ?? 0,
            'total_students' => $completed,
            'completion_rate' => $totalStudents > 0 ? round(($completed / $totalStudents) * 100, 2) : 0,
        ];

        return view('guru.praktikum.show', compact('praktikum', 'scores', 'stats'));
    }

    /**
     * Show the form for editing the practical.
     */
    public function edit(Practical $praktikum): View
    {
        // ✅ Security: Double-check ownership
        if ($praktikum->guru_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('update', $praktikum);

        $subjects = Subject::where('is_active', true)->get();
        $criterias = Criteria::where('is_active', true)->get();
        $classes = \App\Models\Kelas::where('status', 'active')->get();

        $skillLevels = [
            'Pemula' => 'Pemula',
            'Menengah' => 'Menengah',
            'Mahir' => 'Mahir'
        ];

        return view('guru.praktikum.edit', compact('praktikum', 'subjects', 'criterias', 'classes', 'skillLevels'));
    }

    /**
     * Update the specified practical.
     */
    public function update(Request $request, Practical $praktikum): RedirectResponse
    {
        // ✅ Security: Double-check ownership
        if ($praktikum->guru_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('update', $praktikum);

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'lokasi' => 'required|string|max:255',
            'durasi' => 'required|integer|min:1|max:480',
            'skill_level' => 'required|in:Pemula,Menengah,Mahir',
            'tools' => 'required|array|min:1',
            'tools.*' => 'string|max:100',
            'bahan' => 'required|array|min:1',
            'bahan.*' => 'string|max:100',
            'instruksi' => 'required|array|min:1',
            'instruksi.*' => 'string|max:500',
            'keselamatan' => 'required|array|min:1',
            'keselamatan.*' => 'string|max:500',
            'kelas_id' => 'required|exists:kelas,id',
            'max_score' => 'required|integer|min:1|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $praktikum->subject_id = $request->subject_id;
            $praktikum->judul = $request->judul;
            $praktikum->deskripsi = $request->deskripsi;
            $praktikum->tanggal = $request->tanggal;
            $praktikum->waktu_mulai = $request->waktu_mulai;
            $praktikum->waktu_selesai = $request->waktu_selesai;
            $praktikum->lokasi = $request->lokasi;
            $praktikum->durasi = $request->durasi;
            $praktikum->skill_level = $request->skill_level;
            $praktikum->tools = json_encode($request->tools);
            $praktikum->bahan = json_encode($request->bahan);
            $praktikum->instruksi = json_encode($request->instruksi);
            $praktikum->keselamatan = json_encode($request->keselamatan);
            $praktikum->kelas_id = $request->kelas_id;
            $praktikum->max_score = $request->max_score;
            $praktikum->is_published = $request->has('is_published');

            $praktikum->save();

            Log::info('Practical updated', [
                'practical_id' => $praktikum->id,
                'guru_id' => Auth::id(),
                'judul' => $praktikum->judul,
                'ip' => $request->ip()
            ]);

            return redirect()->route('guru.praktikum.index')
                ->with('success', 'Praktikum berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Practical update failed: ' . $e->getMessage(), [
                'practical_id' => $praktikum->id,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified practical.
     */
    public function destroy(Practical $praktikum): RedirectResponse
    {
        // ✅ Security: Double-check ownership
        if ($praktikum->guru_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('delete', $praktikum);

        try {
            PracticalScore::where('practical_id', $praktikum->id)->delete();

            $praktikum->delete();

            Log::info('Practical deleted', [
                'practical_id' => $praktikum->id,
                'guru_id' => Auth::id(),
                'ip' => request()->ip()
            ]);

            return redirect()->route('guru.praktikum.index')
                ->with('success', 'Praktikum berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Practical deletion failed: ' . $e->getMessage(), [
                'practical_id' => $praktikum->id,
                'guru_id' => Auth::id(),
                'ip' => request()->ip()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Score a student for a practical.
     */
    public function scoreSiswa(Request $request, Practical $praktikum): RedirectResponse
    {
        // ✅ Security: Double-check ownership
        if ($praktikum->guru_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('update', $praktikum);

        // ✅ Validasi: Pastikan siswa ada di kelas praktikum
        $siswa = User::where('id', $request->siswa_id)
            ->where('kelas_id', $praktikum->kelas_id)
            ->first();

        if (!$siswa) {
            return back()->withErrors(['siswa_id' => 'Siswa tidak terdaftar di kelas praktikum ini.']);
        }

        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:users,id',
            'criteria_id' => 'required|exists:criterias,id',
            'score' => 'required|numeric|min:0|max:' . $praktikum->max_score,
            'feedback' => 'nullable|string|max:1000',
            'performance_notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            PracticalScore::updateOrCreate(
                [
                    'practical_id' => $praktikum->id,
                    'siswa_id' => $request->siswa_id,
                    'criteria_id' => $request->criteria_id,
                ],
                [
                    'score' => $request->score,
                    'feedback' => $request->feedback,
                    'performance_notes' => $request->performance_notes,
                    'graded_by' => Auth::id(),
                    'graded_at' => now(),
                ]
            );

            Log::info('Practical score saved', [
                'practical_id' => $praktikum->id,
                'siswa_id' => $request->siswa_id,
                'criteria_id' => $request->criteria_id,
                'score' => $request->score,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return back()->with('success', 'Nilai berhasil disimpan!');

        } catch (\Exception $e) {
            Log::error('Practical scoring failed: ' . $e->getMessage(), [
                'practical_id' => $praktikum->id,
                'guru_id' => Auth::id(),
                'ip' => $request->ip()
            ]);

            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Toggle publish status of practical.
     */
    public function togglePublish(Practical $praktikum): RedirectResponse
    {
        // ✅ Security: Double-check ownership
        if ($praktikum->guru_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('update', $praktikum);

        $praktikum->update([
            'is_published' => !$praktikum->is_published
        ]);

        $status = $praktikum->is_published ? 'dipublikasikan' : 'disembunyikan';

        Log::info('Practical publish status toggled', [
            'practical_id' => $praktikum->id,
            'guru_id' => Auth::id(),
            'is_published' => $praktikum->is_published,
            'ip' => request()->ip()
        ]);

        return back()->with('success', "Praktikum berhasil $status!");
    }

    /**
     * Show scoring form for practical.
     */
    public function showScoringForm(Practical $praktikum): View
    {
        // ✅ Security: Double-check ownership
        if ($praktikum->guru_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('update', $praktikum);

        // Ambil SOP (Criteria) sesuai yang diinput admin untuk mata pelajaran praktikum ini
        $criterias = Criteria::where('subject_id', $praktikum->subject_id)
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();
        $siswas = User::where('role', 'siswa')
            ->where('kelas_id', $praktikum->kelas_id)
            ->orderBy('name')
            ->get();

        return view('guru.praktikum.score', compact('praktikum', 'criterias', 'siswas'));
    }

    /**
     * Get student scores for a practical (AJAX).
     */
    public function getSiswaScores(Practical $praktikum, $siswaId): JsonResponse
    {
        // ✅ Security: Double-check ownership
        if ($praktikum->guru_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('view', $praktikum);

        $scores = PracticalScore::with('criteria')
            ->where('practical_id', $praktikum->id)
            ->where('siswa_id', $siswaId)
            ->get();

        return response()->json($scores);
    }
}