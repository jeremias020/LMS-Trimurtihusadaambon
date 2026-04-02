<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserCentral;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class LinkProfilesToUsersCommand extends Command
{
    protected $signature = 'link:profiles-to-users';
    protected $description = 'Link existing profile data to users_central';

    public function handle()
    {
        $this->info('=== LINKING PROFILES TO USERS_CENTRAL ===');
        
        try {
            // Link admin profiles
            $this->linkAdminProfiles();
            
            // Link guru profiles
            $this->linkGuruProfiles();
            
            // Link siswa profiles
            $this->linkSiswaProfiles();
            
            $this->info('✅ All profiles linked successfully!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
    
    private function linkAdminProfiles()
    {
        $this->line('📋 Linking admin profiles...');
        
        $admins = DB::table('admins')->get();
        $linkedCount = 0;
        
        foreach ($admins as $admin) {
            // Find corresponding user in users_central
            $user = UserCentral::where('email', $admin->email)->first();
            
            if ($user) {
                DB::table('admins')
                    ->where('id', $admin->id)
                    ->update(['user_id' => $user->id]);
                
                $this->line("  ✅ Linked admin: {$admin->name} -> User ID: {$user->id}");
                $linkedCount++;
            } else {
                $this->line("  ❌ No user found for admin: {$admin->name}");
            }
        }
        
        $this->line("✅ Linked {$linkedCount} admin profiles");
    }
    
    private function linkGuruProfiles()
    {
        $this->line('👨‍🏫 Linking guru profiles...');
        
        $gurus = DB::table('gurus')->get();
        $linkedCount = 0;
        
        foreach ($gurus as $guru) {
            // Find corresponding user in users_central by email_pribadi or NIP pattern
            $user = null;
            
            if (!empty($guru->email_pribadi)) {
                $user = UserCentral::where('role', 'guru')
                    ->where('email', $guru->email_pribadi)
                    ->first();
            }
            
            if (!$user && !empty($guru->nip)) {
                // Try to find by name pattern (since we don't have name in gurus table)
                $user = UserCentral::where('role', 'guru')
                    ->where('name', 'like', '%Dr.%')
                    ->orWhere('name', 'like', '%Dra.%')
                    ->first();
            }
            
            if ($user) {
                DB::table('gurus')
                    ->where('id', $guru->id)
                    ->update(['user_id' => $user->id]);
                
                $this->line("  ✅ Linked guru (NIP: {$guru->nip}) -> User ID: {$user->id}");
                $linkedCount++;
            } else {
                $this->line("  ❌ No user found for guru (NIP: {$guru->nip})");
            }
        }
        
        $this->line("✅ Linked {$linkedCount} guru profiles");
    }
    
    private function linkSiswaProfiles()
    {
        $this->line('👨‍🎓 Linking siswa profiles...');
        
        $siswas = DB::table('siswa')->get();
        $linkedCount = 0;
        
        foreach ($siswas as $siswa) {
            // Find corresponding user in users_central
            $user = UserCentral::where('role', 'siswa')
                ->where('name', $siswa->name ?? 'Unknown')
                ->first();
            
            if ($user) {
                DB::table('siswa')
                    ->where('id', $siswa->id)
                    ->update(['user_id' => $user->id]);
                
                $this->line("  ✅ Linked siswa: {$siswa->name} -> User ID: {$user->id}");
                $linkedCount++;
            } else {
                $this->line("  ❌ No user found for siswa: {$siswa->name}");
            }
        }
        
        $this->line("✅ Linked {$linkedCount} siswa profiles");
    }
}
