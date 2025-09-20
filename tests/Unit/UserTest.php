<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Student;
use App\Models\Guru;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_correct_fillable_attributes()
    {
        $user = new User();
        $fillable = $user->getFillable();
        
        $expectedFillable = [
            'name', 'email', 'password', 'username', 'role', 'phone',
            'address', 'birth_date', 'gender', 'photo', 'status',
            'email_verified_at', 'remember_token'
        ];
        
        $this->assertEquals($expectedFillable, $fillable);
    }

    public function test_user_has_correct_hidden_attributes()
    {
        $user = new User();
        $hidden = $user->getHidden();
        
        $expectedHidden = ['password', 'remember_token'];
        
        $this->assertEquals($expectedHidden, $hidden);
    }

    public function test_user_has_correct_casts()
    {
        $user = new User();
        $casts = $user->getCasts();
        
        $this->assertArrayHasKey('email_verified_at', $casts);
        $this->assertArrayHasKey('password', $casts);
        $this->assertArrayHasKey('birth_date', $casts);
        $this->assertArrayHasKey('created_at', $casts);
        $this->assertArrayHasKey('updated_at', $casts);
        $this->assertArrayHasKey('deleted_at', $casts);
    }

    public function test_user_has_soft_deletes()
    {
        $user = User::factory()->create();
        $user->delete();
        
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_user_has_siswa_relationship()
    {
        $user = User::factory()->create(['role' => 'siswa']);
        $siswa = Student::factory()->create(['user_id' => $user->id]);
        
        $this->assertInstanceOf(Student::class, $user->siswa);
        $this->assertEquals($siswa->id, $user->siswa->id);
    }

    public function test_user_has_guru_relationship()
    {
        $user = User::factory()->create(['role' => 'guru']);
        $guru = Guru::factory()->create(['user_id' => $user->id]);
        
        $this->assertInstanceOf(Guru::class, $user->guru);
        $this->assertEquals($guru->id, $user->guru->id);
    }

    public function test_user_role_scopes()
    {
        User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'guru']);
        User::factory()->create(['role' => 'siswa']);
        
        $this->assertEquals(1, User::admin()->count());
        $this->assertEquals(1, User::guru()->count());
        $this->assertEquals(1, User::siswa()->count());
    }

    public function test_user_status_scopes()
    {
        User::factory()->create(['status' => 'active']);
        User::factory()->create(['status' => 'inactive']);
        
        $this->assertEquals(1, User::active()->count());
        $this->assertEquals(1, User::inactive()->count());
    }

    public function test_user_role_check_methods()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guru = User::factory()->create(['role' => 'guru']);
        $siswa = User::factory()->create(['role' => 'siswa']);
        
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isGuru());
        $this->assertFalse($admin->isSiswa());
        
        $this->assertTrue($guru->isGuru());
        $this->assertTrue($guru->isTeacher());
        $this->assertFalse($guru->isAdmin());
        
        $this->assertTrue($siswa->isSiswa());
        $this->assertTrue($siswa->isStudent());
        $this->assertFalse($siswa->isAdmin());
    }

    public function test_user_status_check_methods()
    {
        $activeUser = User::factory()->create(['status' => 'active']);
        $inactiveUser = User::factory()->create(['status' => 'inactive']);
        
        $this->assertTrue($activeUser->isActive());
        $this->assertFalse($inactiveUser->isActive());
    }

    public function test_user_photo_url_accessor()
    {
        $user = User::factory()->create(['photo' => 'photos/test.jpg']);
        
        $this->assertStringContains('storage/photos/test.jpg', $user->photo_url);
    }

    public function test_user_photo_url_accessor_without_photo()
    {
        $user = User::factory()->create(['photo' => null]);
        
        $this->assertStringContains('images/default-avatar.png', $user->photo_url);
    }

    public function test_user_age_accessor()
    {
        $user = User::factory()->create(['birth_date' => now()->subYears(25)]);
        
        $this->assertEquals(25, $user->age);
    }

    public function test_user_age_accessor_without_birth_date()
    {
        $user = User::factory()->create(['birth_date' => null]);
        
        $this->assertNull($user->age);
    }

    public function test_user_gender_display_accessor()
    {
        $maleUser = User::factory()->create(['gender' => 'L']);
        $femaleUser = User::factory()->create(['gender' => 'P']);
        
        $this->assertEquals('Laki-laki', $maleUser->gender_display);
        $this->assertEquals('Perempuan', $femaleUser->gender_display);
    }

    public function test_user_role_display_accessor()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guru = User::factory()->create(['role' => 'guru']);
        $siswa = User::factory()->create(['role' => 'siswa']);
        
        $this->assertEquals('Administrator', $admin->role_display);
        $this->assertEquals('Guru', $guru->role_display);
        $this->assertEquals('Siswa', $siswa->role_display);
    }

    public function test_user_permissions()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guru = User::factory()->create(['role' => 'guru']);
        $siswa = User::factory()->create(['role' => 'siswa']);
        
        $this->assertTrue($admin->hasPermission('manage_users'));
        $this->assertFalse($admin->hasPermission('view_materials'));
        
        $this->assertTrue($guru->hasPermission('manage_materials'));
        $this->assertFalse($guru->hasPermission('manage_users'));
        
        $this->assertTrue($siswa->hasPermission('view_materials'));
        $this->assertFalse($siswa->hasPermission('manage_materials'));
    }

    public function test_user_role_check_methods_with_has_role()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('guru'));
    }

    public function test_user_has_any_role()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->assertTrue($user->hasAnyRole(['admin', 'guru']));
        $this->assertFalse($user->hasAnyRole(['guru', 'siswa']));
    }

    public function test_user_can_be_deleted_when_no_related_data()
    {
        $user = User::factory()->create();
        
        $this->assertTrue($user->canBeDeleted());
    }

    public function test_user_can_activate_and_deactivate()
    {
        $user = User::factory()->create(['status' => 'active']);
        
        $user->deactivate();
        $this->assertEquals('inactive', $user->fresh()->status);
        
        $user->activate();
        $this->assertEquals('active', $user->fresh()->status);
    }

    public function test_user_can_change_password()
    {
        $user = User::factory()->create();
        $oldPassword = $user->password;
        
        $user->changePassword('newpassword123');
        
        $this->assertNotEquals($oldPassword, $user->fresh()->password);
    }
}
