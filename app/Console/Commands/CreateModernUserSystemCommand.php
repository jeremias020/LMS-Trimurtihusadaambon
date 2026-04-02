<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateModernUserSystemCommand extends Command
{
    protected $signature = 'create:modern-user-system';
    protected $description = 'Create modern central user system with proper architecture';

    public function handle()
    {
        $this->info('=== CREATING MODERN USER SYSTEM ===');
        
        try {
            // Step 1: Create users_central table with proper structure
            $this->line('📋 Creating users_central table...');
            $this->createUsersCentralTable();
            
            // Step 2: Migrate from original users table
            $this->line('🔄 Migrating from original users table...');
            $this->migrateFromOriginalUsers();
            
            // Step 3: Update profile tables to reference user_id
            $this->line('🔗 Updating profile tables...');
            $this->updateProfileTables();
            
            // Step 4: Create new User model for central authentication
            $this->line('📝 Creating new User model...');
            $this->createNewUserModel();
            
            $this->info('✅ Modern user system created successfully!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
    
    private function createUsersCentralTable()
    {
        Schema::dropIfExists('users_central');
        
        Schema::create('users_central', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('username')->unique();
            $table->enum('role', ['admin', 'guru', 'siswa']);
            $table->string('phone', 20)->nullable();
            $table->string('photo', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('email');
            $table->index('username');
            $table->index('role');
            $table->index('is_active');
        });
        
        $this->line('✅ users_central table created');
    }
    
    private function migrateFromOriginalUsers()
    {
        // Get users from original users table
        $originalUsers = DB::table('users')->get();
        
        $centralUsers = [];
        foreach ($originalUsers as $user) {
            $centralUsers[] = [
                'name' => $user->name ?? '',
                'email' => $user->email ?? '',
                'password' => $user->password ?? '',
                'username' => $user->username ?? 'user_' . $user->id,
                'role' => $user->role ?? 'siswa',
                'phone' => $user->phone ?? '',
                'photo' => $user->avatar ?? '',
                'is_active' => $user->is_active ?? true,
                'email_verified_at' => $user->email_verified_at ?? null,
                'created_at' => $user->created_at ?? now(),
                'updated_at' => $user->updated_at ?? now(),
                'deleted_at' => $user->deleted_at ?? null,
            ];
        }
        
        if (!empty($centralUsers)) {
            DB::table('users_central')->insert($centralUsers);
            $this->line('✅ Migrated ' . count($centralUsers) . ' users from original table');
        }
    }
    
    private function updateProfileTables()
    {
        // Update admins table
        Schema::table('admins', function ($table) {
            if (!Schema::hasColumn('admins', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
                $table->foreign('user_id')->references('id')->on('users_central')->onDelete('cascade');
            }
        });
        
        // Update gurus table  
        Schema::table('gurus', function ($table) {
            if (!Schema::hasColumn('gurus', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
                $table->foreign('user_id')->references('id')->on('users_central')->onDelete('cascade');
            }
        });
        
        // Update siswa table
        Schema::table('siswa', function ($table) {
            if (!Schema::hasColumn('siswa', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
                $table->foreign('user_id')->references('id')->on('users_central')->onDelete('cascade');
            }
        });
        
        $this->line('✅ Profile tables updated with user_id references');
    }
    
    private function createNewUserModel()
    {
        $modelContent = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserCentral extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = \'users_central\';

    protected $fillable = [
        \'name\',
        \'email\',
        \'password\',
        \'username\',
        \'role\',
        \'phone\',
        \'photo\',
        \'is_active\',
        \'email_verified_at\',
    ];

    protected $hidden = [
        \'password\',
        \'remember_token\',
    ];

    protected $casts = [
        \'email_verified_at\' => \'datetime\',
        \'password\' => \'hashed\',
        \'is_active\' => \'boolean\',
        \'created_at\' => \'datetime\',
        \'updated_at\' => \'datetime\',
        \'deleted_at\' => \'datetime\',
    ];

    protected $appends = [\'photo_url\', \'role_display\'];

    // Accessors
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset(\'storage/\' . $this->photo);
        }
        return \'https://ui-avatars.com/api/?name=\' . urlencode($this->name) . \'&color=7F9CF5&background=EBF4FF\';
    }

    public function getRoleDisplayAttribute()
    {
        return match($this->role) {
            \'admin\' => \'Administrator\',
            \'guru\' => \'Guru\',
            \'siswa\' => \'Siswa\',
            default => $this->role
        };
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where(\'is_active\', true);
    }

    public function scopeInactive($query)
    {
        return $query->where(\'is_active\', false);
    }

    public function scopeAdmin($query)
    {
        return $query->where(\'role\', \'admin\');
    }

    public function scopeGuru($query)
    {
        return $query->where(\'role\', \'guru\');
    }

    public function scopeSiswa($query)
    {
        return $query->where(\'role\', \'siswa\');
    }

    // Methods
    public function isActive()
    {
        return $this->is_active;
    }

    public function isAdmin()
    {
        return $this->role === \'admin\';
    }

    public function isGuru()
    {
        return $this->role === \'guru\';
    }

    public function isSiswa()
    {
        return $this->role === \'siswa\';
    }

    // Relationships
    public function adminProfile(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    public function guruProfile(): HasOne
    {
        return $this->hasOne(Guru::class);
    }

    public function siswaProfile(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function getProfileAttribute()
    {
        return match($this->role) {
            \'admin\' => $this->adminProfile,
            \'guru\' => $this->guruProfile,
            \'siswa\' => $this->siswaProfile,
            default => null
        };
    }
}';

        file_put_contents(app_path('Models/UserCentral.php'), $modelContent);
        $this->line('✅ UserCentral model created');
    }
}
