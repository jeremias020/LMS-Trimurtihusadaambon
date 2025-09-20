<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada user. Profile tidak bisa dibuat.');
            return;
        }

        // Hapus semua profile lama agar tidak duplikat (karena user_id unique)
        Profile::whereNotNull('id')->delete();

        foreach ($users as $user) {
            Profile::create([
                'user_id' => $user->id,
                'avatar' => null,
                'phone' => $user->phone,
                'address' => $user->address,
                'bio' => $this->getBioBasedOnRole($user->role),
                'date_of_birth' => $user->birth_date,
                'gender' => $user->gender, // ← langsung assign, tidak perlu ternary
                'emergency_contact' => $this->getEmergencyContact($user),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $count = $users->count();
        $this->command->info("✅ ProfileSeeder: {$count} profil berhasil disimpan.");
    }

    private function getBioBasedOnRole($role)
    {
        return match($role) {
            'admin' => 'Administrator sistem LMS SMK Kesehatan Trimurti Husada Ambon',
            'guru' => 'Guru pengajar di SMK Kesehatan Trimurti Husada Ambon',
            'siswa' => 'Siswa SMK Kesehatan Trimurti Husada Ambon',
            default => 'Pengguna sistem LMS'
        };
    }

    private function getEmergencyContact($user)
    {
        return $user->phone ?? '081234567890';
    }
}
