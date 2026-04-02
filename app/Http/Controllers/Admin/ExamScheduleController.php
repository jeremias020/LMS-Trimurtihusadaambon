<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamSchedule;
use App\Models\SystemNotification;
use App\Models\User;
use App\Models\Student;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $schedules = ExamSchedule::with(['subject', 'kelas', 'creator'])
            ->when($request->get('search'), function($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->get('exam_type'), function($query, $type) {
                $query->where('exam_type', $type);
            })
            ->when($request->get('kelas_id'), function($query, $kelasId) {
                $query->where('kelas_id', $kelasId);
            })
            ->latest()
            ->paginate(10);

        return view('admin.exam-schedules.index', compact('schedules'));
    }

    public function create()
    {
        $subjects = \App\Models\Subject::where('is_active', true)->get();
        $kelas = \App\Models\Kelas::all();
        
        return view('admin.exam-schedules.create', compact('subjects', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exam_type' => 'required|in:uts,uas,quiz,praktikum,lainnya',
            'subject_id' => 'required|exists:subjects,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'location' => 'nullable|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'is_published' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $schedule = ExamSchedule::create([
                'title' => $request->title,
                'description' => $request->description,
                'exam_type' => $request->exam_type,
                'subject_id' => $request->subject_id,
                'kelas_id' => $request->kelas_id,
                'created_by' => auth()->id(),
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
                'duration_minutes' => $request->duration_minutes,
                'is_published' => $request->boolean('is_published', false),
            ]);

            // Send notifications if published
            if ($schedule->is_published) {
                try {
                    $this->sendExamNotifications($schedule);
                } catch (\Exception $e) {
                    // Log notification error but don't fail the schedule creation
                    Log::warning('Notification failed but schedule created: ' . $e->getMessage());
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.exam-schedules.index')
                ->with('success', 'Jadwal ujian berhasil dibuat' . ($schedule->is_published ? ' dan notifikasi telah dikirim' : ''));
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating exam schedule: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat jadwal ujian');
        }
    }

    public function show(ExamSchedule $examSchedule)
    {
        $examSchedule->load(['subject', 'kelas', 'creator']);
        
        return view('admin.exam-schedules.show', compact('examSchedule'));
    }

    public function edit(ExamSchedule $examSchedule)
    {
        $subjects = \App\Models\Subject::where('is_active', true)->get();
        $kelas = \App\Models\Kelas::all();
        
        return view('admin.exam-schedules.edit', compact('examSchedule', 'subjects', 'kelas'));
    }

    public function update(Request $request, ExamSchedule $examSchedule)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exam_type' => 'required|in:uts,uas,quiz,praktikum,lainnya',
            'subject_id' => 'required|exists:subjects,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'nullable|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'is_published' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $wasPublished = $examSchedule->is_published;
            
            $examSchedule->update([
                'title' => $request->title,
                'description' => $request->description,
                'exam_type' => $request->exam_type,
                'subject_id' => $request->subject_id,
                'kelas_id' => $request->kelas_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
                'duration_minutes' => $request->duration_minutes,
                'is_published' => $request->boolean('is_published', false),
            ]);

            // Send notifications if newly published
            if (!$wasPublished && $examSchedule->is_published) {
                $this->sendExamNotifications($examSchedule);
            }

            DB::commit();

            return redirect()
                ->route('admin.exam-schedules.index')
                ->with('success', 'Jadwal ujian berhasil diperbarui' . (!$wasPublished && $examSchedule->is_published ? ' dan notifikasi telah dikirim' : ''));
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating exam schedule: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui jadwal ujian');
        }
    }

    public function destroy(ExamSchedule $examSchedule)
    {
        try {
            $examSchedule->delete();
            
            return redirect()
                ->route('admin.exam-schedules.index')
                ->with('success', 'Jadwal ujian berhasil dihapus');
                
        } catch (\Exception $e) {
            Log::error('Error deleting exam schedule: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Terjadi kesalahan saat menghapus jadwal ujian');
        }
    }

    public function publish(ExamSchedule $examSchedule)
    {
        try {
            DB::beginTransaction();

            $examSchedule->update(['is_published' => true]);
            $this->sendExamNotifications($examSchedule);

            DB::commit();

            return back()->with('success', 'Jadwal ujian telah dipublikasikan dan notifikasi dikirim');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error publishing exam schedule: ' . $e->getMessage());
            
            return back()->with('error', 'Terjadi kesalahan saat mempublikasikan jadwal ujian');
        }
    }

    private function sendExamNotifications(ExamSchedule $schedule)
    {
        // Load relationships
        $schedule->load(['subject', 'kelas']);
        
        $examTypeLabels = [
            'uts' => 'UTS',
            'uas' => 'UAS',
            'quiz' => 'Quiz',
            'praktikum' => 'Praktikum',
            'lainnya' => 'Ujian'
        ];

        $subjectName = $schedule->subject ? $schedule->subject->nama : 'Mata Pelajaran';
        $title = "Jadwal {$examTypeLabels[$schedule->exam_type]}: {$schedule->title}";
        $message = "Jadwal {$examTypeLabels[$schedule->exam_type]} untuk mata pelajaran {$subjectName} akan dimulai pada " . 
                   $schedule->start_time->format('d M Y H:i') . 
                   ($schedule->location ? " di {$schedule->location}" : "");

        $actionUrl = route('exam-schedules.show', $schedule->id);

        // Get users to notify
        $usersToNotify = collect();

        if ($schedule->kelas_id) {
            // Notify students and teachers in specific class
            $students = Student::where('kelas_id', $schedule->kelas_id)->get();
            $gurus = Guru::where('kelas_id', $schedule->kelas_id)->get();
            
            $usersToNotify = $usersToNotify
                ->merge($students->pluck('user_id'))
                ->merge($gurus->pluck('user_id'));
        } else {
            // Notify all students and teachers
            $usersToNotify = $usersToNotify
                ->merge(User::where('role', 'siswa')->pluck('id'))
                ->merge(User::where('role', 'guru')->pluck('id'));
        }

        // Create notifications
        foreach ($usersToNotify as $userId) {
            SystemNotification::create([
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => 'exam',
                'action_url' => $actionUrl,
                'data' => [
                    'exam_schedule_id' => $schedule->id,
                    'exam_type' => $schedule->exam_type,
                    'start_time' => $schedule->start_time->toISOString(),
                    'location' => $schedule->location,
                ]
            ]);
        }
    }
}
