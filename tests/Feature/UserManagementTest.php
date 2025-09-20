<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Guru;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_users_index()
    {
        $this->actingAs($this->admin);
        
        $response = $this->get('/admin/users');
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
    }

    public function test_admin_can_create_user()
    {
        $this->actingAs($this->admin);
        
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'siswa',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 123',
            'birth_date' => '2000-01-01',
            'gender' => 'L'
        ];

        $response = $this->post('/admin/users', $userData);
        $response->assertRedirect('/admin/users');
        
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'siswa'
        ]);
    }

    public function test_admin_can_update_user()
    {
        $this->actingAs($this->admin);
        
        $user = User::factory()->create(['role' => 'siswa']);
        
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'siswa',
            'phone' => '081234567890'
        ];

        $response = $this->put("/admin/users/{$user->id}", $updateData);
        $response->assertRedirect('/admin/users');
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);
    }

    public function test_admin_can_delete_user()
    {
        $this->actingAs($this->admin);
        
        $user = User::factory()->create();
        
        $response = $this->delete("/admin/users/{$user->id}");
        $response->assertRedirect('/admin/users');
        
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_admin_can_update_user_status()
    {
        $this->actingAs($this->admin);
        
        $user = User::factory()->create(['status' => 'active']);
        
        $response = $this->post("/admin/users/{$user->id}/status", [
            'status' => 'inactive'
        ]);
        
        $response->assertRedirect();
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'inactive'
        ]);
    }

    public function test_student_profile_is_created_when_user_role_is_siswa()
    {
        $this->actingAs($this->admin);
        
        $userData = [
            'name' => 'Student Name',
            'email' => 'student@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'siswa'
        ];

        $response = $this->post('/admin/users', $userData);
        
        $user = User::where('email', 'student@example.com')->first();
        $this->assertDatabaseHas('siswa', ['user_id' => $user->id]);
    }

    public function test_guru_profile_is_created_when_user_role_is_guru()
    {
        $this->actingAs($this->admin);
        
        $userData = [
            'name' => 'Guru Name',
            'email' => 'guru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'guru'
        ];

        $response = $this->post('/admin/users', $userData);
        
        $user = User::where('email', 'guru@example.com')->first();
        $this->assertDatabaseHas('gurus', ['user_id' => $user->id]);
    }

    public function test_non_admin_cannot_access_user_management()
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $this->actingAs($guru);
        
        $response = $this->get('/admin/users');
        $response->assertStatus(403);
    }

    public function test_user_validation_requires_email()
    {
        $this->actingAs($this->admin);
        
        $userData = [
            'name' => 'Test User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'siswa'
        ];

        $response = $this->post('/admin/users', $userData);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_user_validation_requires_unique_email()
    {
        $this->actingAs($this->admin);
        
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);
        
        $userData = [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'siswa'
        ];

        $response = $this->post('/admin/users', $userData);
        $response->assertSessionHasErrors(['email']);
    }
}
