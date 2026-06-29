<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\UserCentral;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Daftar notifikasi yang sudah dikirim.
     */
    public function index(): View
    {
        $notifications = Notification::with('createdBy')
            ->latest()
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Form kirim notifikasi baru.
     */
    public function create(): View
    {
        $users = UserCentral::select('id', 'name', 'email', 'role')
            ->whereIn('role', ['guru', 'siswa'])
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        return view('admin.notifications.create', compact('users'));
    }

    /**
     * Simpan & kirim notifikasi.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'message'       => 'required|string|max:2000',
            'target'        => 'required|in:semua,guru,siswa,user',
            'target_users'  => 'required_if:target,user|array',
            'target_users.*'=> 'integer|exists:users_central,id',
            'tipe'          => 'nullable|in:info,warning,success,error,announcement',
            'action_url'    => 'nullable|url|max:500',
        ]);

        $adminId = Auth::id();
        $title   = $request->title;
        $message = $request->message;
        $tipe    = $request->tipe ?? 'info';
        $url     = $request->action_url;
        $target  = $request->target;

        $recipients = collect(); // collection of user_id

        if ($target === 'semua') {
            // Satu baris dengan tipe_penerima = 'semua' — cukup untuk semua user
            $this->createNotification($title, $message, $tipe, $url, $adminId, null, 'semua');
            $sent = 1;
        } elseif ($target === 'guru') {
            $recipients = UserCentral::where('role', 'guru')->pluck('id');
        } elseif ($target === 'siswa') {
            $recipients = UserCentral::where('role', 'siswa')->pluck('id');
        } else {
            $recipients = collect($request->target_users);
        }

        if ($recipients->isNotEmpty()) {
            foreach ($recipients as $userId) {
                $this->createNotification($title, $message, $tipe, $url, $adminId, $userId, 'user');
            }
            $sent = $recipients->count();
        }

        // Invalidate cache notifikasi semua penerima
        Cache::flush();

        return redirect()->route('admin.notifications.index')
            ->with('success', "Notifikasi berhasil dikirim ke {$sent} penerima.");
    }

    /**
     * Hapus notifikasi.
     */
    public function destroy(Notification $notification): RedirectResponse
    {
        $notification->delete();
        Cache::flush();

        return back()->with('success', 'Notifikasi dihapus.');
    }

    // ── Helper ──────────────────────────────────────────────────────────────

    private function createNotification(
        string  $title,
        string  $message,
        string  $tipe,
        ?string $actionUrl,
        int     $createdBy,
        ?int    $penerimaId,
        string  $tipePenerima
    ): void {
        Notification::create([
            'title'         => $title,
            'message'       => $message,
            'judul'         => $title,
            'pesan'         => $message,
            'tipe'          => $tipe,
            'type'          => $tipe,
            'tipe_notifikasi' => $tipe,
            'action_url'    => $actionUrl,
            'url_aksi'      => $actionUrl,
            'penerima_id'   => $penerimaId,
            'receiver_id'   => $penerimaId,
            'tipe_penerima' => $tipePenerima,
            'receiver_type' => $tipePenerima,
            'is_read'       => false,
            'status'        => 'belum_dibaca',
            'prioritas'     => 'sedang',
            'priority'      => 'medium',
            'created_by'    => $createdBy,
            'pengirim_id'   => $createdBy,
            'sender_id'     => $createdBy,
        ]);
    }
}
