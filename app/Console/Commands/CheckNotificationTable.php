<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckNotificationTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check notification table structure and fix if needed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking notifications table...');
        
        // Check if table exists
        if (!Schema::hasTable('notifications')) {
            $this->error('Notifications table does not exist!');
            return;
        }
        
        $this->info('✅ Notifications table exists');
        
        // Get table structure
        $columns = DB::select('DESCRIBE notifications');
        
        $this->info('Current table structure:');
        foreach ($columns as $column) {
            $this->line('- ' . $column->Field . ' (' . $column->Type . ')');
        }
        
        // Check required columns
        $requiredColumns = ['penerima_id', 'pengirim_id', 'tipe_penerima', 'tipe', 'judul', 'pesan', 'url_aksi', 'prioritas', 'status'];
        $existingColumns = collect($columns)->pluck('Field')->toArray();
        
        $missingColumns = array_diff($requiredColumns, $existingColumns);
        
        if (!empty($missingColumns)) {
            $this->error('Missing columns: ' . implode(', ', $missingColumns));
            
            if ($this->confirm('Do you want to add missing columns?')) {
                $this->addMissingColumns($missingColumns);
            }
        } else {
            $this->info('✅ All required columns exist');
        }
        
        // Test query
        try {
            $count = DB::table('notifications')
                ->where(function($query) {
                    $query->where('penerima_id', 1)
                          ->orWhere('tipe_penerima', 'semua');
                })
                ->count();
            
            $this->info("✅ Test query successful. Found {$count} notifications.");
        } catch (\Exception $e) {
            $this->error('❌ Test query failed: ' . $e->getMessage());
        }
    }
    
    private function addMissingColumns($missingColumns)
    {
        Schema::table('notifications', function($table) use ($missingColumns) {
            foreach ($missingColumns as $column) {
                switch ($column) {
                    case 'penerima_id':
                        $table->foreignId('penerima_id')->nullable()->constrained('users')->onDelete('cascade');
                        break;
                    case 'pengirim_id':
                        $table->foreignId('pengirim_id')->nullable()->constrained('users')->onDelete('set null');
                        break;
                    case 'tipe_penerima':
                        $table->enum('tipe_penerima', ['siswa', 'guru', 'admin', 'semua'])->default('semua');
                        break;
                    case 'tipe':
                        $table->enum('tipe', ['info', 'peringatan', 'sukses', 'error', 'sistem'])->default('info');
                        break;
                    case 'judul':
                        $table->string('judul')->default('Notification');
                        break;
                    case 'pesan':
                        $table->text('pesan')->nullable();
                        break;
                    case 'url_aksi':
                        $table->string('url_aksi')->nullable();
                        break;
                    case 'prioritas':
                        $table->enum('prioritas', ['rendah', 'sedang', 'tinggi', 'darurat'])->default('sedang');
                        break;
                    case 'status':
                        $table->enum('status', ['belum_dibaca', 'terbaca', 'diarsipkan'])->default('belum_dibaca');
                        break;
                }
            }
        });
        
        $this->info('✅ Missing columns added successfully!');
    }
}
