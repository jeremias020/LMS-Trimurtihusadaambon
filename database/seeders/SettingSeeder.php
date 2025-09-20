<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama jika ada (karena biasanya hanya 1 record)
        Setting::truncate(); // atau: Setting::whereNotNull('id')->delete();

        $settingsData = [
            'site_name' => 'LMS SMK Kesehatan Trimurti Husada Ambon',
            'contact_email' => 'info@trimurti.sch.id',
            'phone_number' => '(0911) 362734',
            'address' => 'Jl. Pendidikan No. 123, Ambon, Maluku',
            'about' => 'Sistem Learning Management System untuk SMK Kesehatan Trimurti Husada Ambon yang menyediakan platform pembelajaran digital untuk siswa dan guru.',
            'logo' => 'logo.png',
            'favicon' => 'favicon.ico',
            'facebook_url' => 'https://facebook.com/trimurtihusada',     // ← DIPERBAIKI: hapus spasi ekstra
            'twitter_url' => 'https://twitter.com/trimurtihusada',      // ← DIPERBAIKI
            'instagram_url' => 'https://instagram.com/trimurtihusada',  // ← DIPERBAIKI
            'youtube_url' => 'https://youtube.com/trimurtihusada',      // ← DIPERBAIKI
            'meta_title' => 'LMS SMK Kesehatan Trimurti Husada Ambon',
            'meta_description' => 'Sistem pembelajaran online untuk SMK Kesehatan Trimurti Husada Ambon',
            'meta_keywords' => 'lms, smk, kesehatan, trimurti, husada, ambon, pembelajaran, online',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        Setting::create($settingsData);

        $this->command->info('✅ SettingSeeder: Pengaturan sistem berhasil disimpan.');
    }
}
