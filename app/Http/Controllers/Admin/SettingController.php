<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    /**
     * Kunci-kunci pengaturan yang disimpan di file JSON.
     */
    private const SETTINGS_FILE = 'settings/app_settings.json';

    /**
     * Nilai default pengaturan.
     */
    private function defaults(): array
    {
        return [
            'site_name'                  => 'LMS Trimurti Husada',
            'contact_email'              => 'admin@trimurtihusada.sch.id',
            'phone_number'               => '',
            'address'                    => 'Jl. Raya Ambon, Maluku',
            'logo'                       => null,
            'favicon'                    => null,
            'academic_year'              => date('Y') . '/' . (date('Y') + 1),
            'semester'                   => '1',
            'min_attendance_percentage'  => 75,
            'passing_grade'              => 75,
            'practical_min_score'        => 70,
            'max_upload_size'            => 10,
            'max_files_per_upload'       => 5,
            'allowed_file_types'         => 'pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,zip,rar',
            'email_notifications'        => true,
            'assignment_reminders'       => true,
            'attendance_alerts'          => true,
            'grade_notifications'        => true,
            'practical_reminders'        => true,
            'maintenance_mode'           => false,
            'backup_frequency'           => 'daily',
            'session_lifetime'           => 120,
        ];
    }

    /**
     * Baca pengaturan dari storage.
     */
    private function readSettings(): object
    {
        try {
            if (Storage::disk('local')->exists(self::SETTINGS_FILE)) {
                $json = Storage::disk('local')->get(self::SETTINGS_FILE);
                $data = json_decode($json, true);
                if (is_array($data)) {
                    return (object) array_merge($this->defaults(), $data);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Could not read settings file: ' . $e->getMessage());
        }

        return (object) $this->defaults();
    }

    /**
     * Simpan pengaturan ke storage.
     */
    private function writeSettings(array $data): void
    {
        Storage::disk('local')->put(
            self::SETTINGS_FILE,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    /**
     * Display the settings page.
     */
    public function index(): View
    {
        $settings = $this->readSettings();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'site_name'                 => 'required|string|max:255',
            'contact_email'             => 'required|email|max:255',
            'phone_number'              => 'nullable|string|max:30',
            'address'                   => 'nullable|string|max:500',
            'logo'                      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'favicon'                   => 'nullable|image|mimes:png,ico|max:512',
            'academic_year'             => 'required|string|max:20',
            'semester'                  => 'required|in:1,2',
            'min_attendance_percentage' => 'required|integer|min:0|max:100',
            'passing_grade'             => 'required|numeric|min:0|max:100',
            'practical_min_score'       => 'required|numeric|min:0|max:100',
            'max_upload_size'           => 'required|integer|min:1|max:100',
            'max_files_per_upload'      => 'required|integer|min:1|max:50',
            'allowed_file_types'        => 'required|string|max:500',
            'session_lifetime'          => 'required|integer|min:5|max:1440',
            'backup_frequency'          => 'required|in:daily,weekly,monthly',
        ]);

        try {
            // Baca pengaturan lama
            $current = (array) $this->readSettings();

            // Update nilai teks
            $current['site_name']                  = $request->site_name;
            $current['contact_email']              = $request->contact_email;
            $current['phone_number']               = $request->phone_number;
            $current['address']                    = $request->address;
            $current['academic_year']              = $request->academic_year;
            $current['semester']                   = $request->semester;
            $current['min_attendance_percentage']  = (int) $request->min_attendance_percentage;
            $current['passing_grade']              = (float) $request->passing_grade;
            $current['practical_min_score']        = (float) $request->practical_min_score;
            $current['max_upload_size']            = (int) $request->max_upload_size;
            $current['max_files_per_upload']       = (int) $request->max_files_per_upload;
            $current['allowed_file_types']         = $request->allowed_file_types;
            $current['session_lifetime']           = (int) $request->session_lifetime;
            $current['backup_frequency']           = $request->backup_frequency;

            // Checkbox (boolean)
            $current['email_notifications']   = $request->boolean('email_notifications');
            $current['assignment_reminders']  = $request->boolean('assignment_reminders');
            $current['attendance_alerts']     = $request->boolean('attendance_alerts');
            $current['grade_notifications']   = $request->boolean('grade_notifications');
            $current['practical_reminders']   = $request->boolean('practical_reminders');
            $current['maintenance_mode']      = $request->boolean('maintenance_mode');

            // Upload logo
            if ($request->hasFile('logo')) {
                if (!empty($current['logo'])) {
                    Storage::disk('public')->delete($current['logo']);
                }
                $current['logo'] = $request->file('logo')->store('settings', 'public');
            }

            // Upload favicon
            if ($request->hasFile('favicon')) {
                if (!empty($current['favicon'])) {
                    Storage::disk('public')->delete($current['favicon']);
                }
                $current['favicon'] = $request->file('favicon')->store('settings', 'public');
            }

            $this->writeSettings($current);

            Log::info('Settings updated by admin', ['admin_id' => auth()->id()]);

            return redirect()->route('admin.settings.index')
                ->with('success', 'Pengaturan berhasil disimpan.');

        } catch (\Exception $e) {
            Log::error('Settings update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pengaturan: ' . $e->getMessage());
        }
    }
}
