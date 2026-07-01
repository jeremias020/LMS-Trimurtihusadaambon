<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Buat admin default jika belum ada
        $exists = DB::table('users_central')
            ->where('email', 'admin@lmstrimurti.com')
            ->exists();

        if (!$exists) {
            DB::table('users_central')->insert([
                'name'       => 'Admin',
                'email'      => 'admin@lmstrimurti.com',
                'password'   => Hash::make('Admin123!'),
                'role'       => 'admin',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            echo "✅ Admin default dibuat: admin@lmstrimurti.com / Admin123!\n";
        } else {
            echo "ℹ️ Admin sudah ada.\n";
        }
    }
}
