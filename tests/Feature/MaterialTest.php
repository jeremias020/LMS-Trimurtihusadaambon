<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Material;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MaterialTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guru = User::factory()->create(['role' => 'guru']);
        $this->siswa = User::factory()->create(['role' => 'siswa']);
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_guru_can_view_materials_index()
    {
        $this->actingAs($this->guru);
        
        $response = $this->get('/guru/materials');
        $response->assertStatus(200);
        $response->assertViewIs('guru.materials.index');
    }

    public function test_guru_can_create_material()
    {
        $this->actingAs($this->guru);
        
        Storage::fake('public');
        $file = UploadedFile::fake()->create('document.pdf', 1000);
        
        $materialData = [
            'judul' => 'Test Material',
            'description' => 'This is a test material',
            'file' => $file,
            'is_published' => true
        ];

        $response = $this->post('/guru/materials', $materialData);
        $response->assertRedirect('/guru/materials');
        
        $this->assertDatabaseHas('materials', [
            'judul' => 'Test Material',
            'guru_id' => $this->guru->id,
            'is_published' => true
        ]);
        
        Storage::disk('public')->assertExists('materials/' . $file->hashName());
    }

    public function test_guru_can_update_material()
    {
        $this->actingAs($this->guru);
        
        $material = Material::factory()->create(['guru_id' => $this->guru->id]);
        
        $updateData = [
            'judul' => 'Updated Material',
            'description' => 'Updated description',
            'is_published' => false
        ];

        $response = $this->put("/guru/materials/{$material->id}", $updateData);
        $response->assertRedirect('/guru/materials');
        
        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'judul' => 'Updated Material',
            'is_published' => false
        ]);
    }

    public function test_guru_can_delete_material()
    {
        $this->actingAs($this->guru);
        
        $material = Material::factory()->create(['guru_id' => $this->guru->id]);
        
        $response = $this->delete("/guru/materials/{$material->id}");
        $response->assertRedirect('/guru/materials');
        
        $this->assertSoftDeleted('materials', ['id' => $material->id]);
    }

    public function test_guru_can_publish_material()
    {
        $this->actingAs($this->guru);
        
        $material = Material::factory()->create([
            'guru_id' => $this->guru->id,
            'is_published' => false
        ]);
        
        $response = $this->post("/guru/materials/{$material->id}/publish");
        $response->assertRedirect();
        
        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'is_published' => true
        ]);
    }

    public function test_guru_can_unpublish_material()
    {
        $this->actingAs($this->guru);
        
        $material = Material::factory()->create([
            'guru_id' => $this->guru->id,
            'is_published' => true
        ]);
        
        $response = $this->post("/guru/materials/{$material->id}/unpublish");
        $response->assertRedirect();
        
        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'is_published' => false
        ]);
    }

    public function test_siswa_can_view_published_materials()
    {
        $this->actingAs($this->siswa);
        
        $material = Material::factory()->create(['is_published' => true]);
        
        $response = $this->get('/siswa/materials');
        $response->assertStatus(200);
        $response->assertViewIs('siswa.materials.index');
    }

    public function test_siswa_cannot_view_unpublished_materials()
    {
        $this->actingAs($this->siswa);
        
        $material = Material::factory()->create(['is_published' => false]);
        
        $response = $this->get('/siswa/materials');
        $response->assertStatus(200);
        $response->assertDontSee($material->judul);
    }

    public function test_siswa_can_download_material()
    {
        $this->actingAs($this->siswa);
        
        Storage::fake('public');
        $file = UploadedFile::fake()->create('document.pdf', 1000);
        $filePath = $file->store('materials', 'public');
        
        $material = Material::factory()->create([
            'file' => $filePath,
            'is_published' => true
        ]);
        
        $response = $this->get("/siswa/materials/{$material->id}/download");
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_material_views_count_increments_on_view()
    {
        $this->actingAs($this->siswa);
        
        $material = Material::factory()->create([
            'views_count' => 0,
            'is_published' => true
        ]);
        
        $response = $this->get("/siswa/materials/{$material->id}");
        $response->assertStatus(200);
        
        $material->refresh();
        $this->assertEquals(1, $material->views_count);
    }

    public function test_material_downloads_count_increments_on_download()
    {
        $this->actingAs($this->siswa);
        
        Storage::fake('public');
        $file = UploadedFile::fake()->create('document.pdf', 1000);
        $filePath = $file->store('materials', 'public');
        
        $material = Material::factory()->create([
            'file' => $filePath,
            'downloads_count' => 0,
            'is_published' => true
        ]);
        
        $response = $this->get("/siswa/materials/{$material->id}/download");
        $response->assertStatus(200);
        
        $material->refresh();
        $this->assertEquals(1, $material->downloads_count);
    }

    public function test_guru_cannot_access_other_guru_materials()
    {
        $otherGuru = User::factory()->create(['role' => 'guru']);
        $this->actingAs($this->guru);
        
        $material = Material::factory()->create(['guru_id' => $otherGuru->id]);
        
        $response = $this->get("/guru/materials/{$material->id}/edit");
        $response->assertStatus(403);
    }

    public function test_material_validation_requires_title()
    {
        $this->actingAs($this->guru);
        
        $materialData = [
            'description' => 'Test description',
            'is_published' => true
        ];

        $response = $this->post('/guru/materials', $materialData);
        $response->assertSessionHasErrors(['judul']);
    }
}
