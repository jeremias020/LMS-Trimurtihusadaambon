<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

// Inspirational quote command
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Custom console commands
Artisan::command('lms:setup', function () {
    $this->info('Setting up LMS Trimurti Husada...');

    // Run migrations
    $this->call('migrate:fresh', ['--seed' => true]);

    // Create storage links
    $this->call('storage:link');

    // Clear caches
    $this->call('optimize:clear');

    $this->info('LMS setup completed successfully!');
})->describe('Setup the LMS application');

Artisan::command('lms:reset-demo', function () {
    $this->info('Resetting demo data...');

    // Reset to demo state
    $this->call('migrate:fresh', ['--seed' => true]);

    // Jika Anda punya DemoSeeder, uncomment baris berikut:
    // $this->call('db:seed', ['--class' => 'DemoSeeder']);

    $this->info('Demo data reset successfully!');
})->describe('Reset the application to demo state');

// ❌ Hapus atau comment jika package backup belum diinstal
// Artisan::command('lms:backup', function () {
//     $this->info('Creating application backup...');
//     $this->call('backup:run', ['--only-db' => true]);
//     $this->info('Backup created successfully!');
// })->describe('Create a backup of the application');
