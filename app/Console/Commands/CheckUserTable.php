<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckUserTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check users table structure';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking users table...');
        
        if (!Schema::hasTable('users')) {
            $this->error('Users table does not exist!');
            return;
        }
        
        $this->info('✅ Users table exists');
        
        // Get table structure
        $columns = DB::select('DESCRIBE users');
        
        $this->info('Current table structure:');
        foreach ($columns as $column) {
            $this->line('- ' . $column->Field . ' (' . $column->Type . ')');
        }
        
        // Check if username column exists
        if (Schema::hasColumn('users', 'username')) {
            $this->info('✅ Username column exists');
        } else {
            $this->error('❌ Username column missing');
            
            if ($this->confirm('Add username column?')) {
                Schema::table('users', function($table) {
                    $table->string('username')->unique()->nullable()->after('email');
                    $table->index('username');
                });
                $this->info('✅ Username column added');
            }
        }
    }
}
