<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'guru_id' => User::factory()->guru(),
            'judul' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'file' => null,
            'file_size' => fake()->numberBetween(1000, 10000000),
            'file_type' => fake()->randomElement(['pdf', 'doc', 'docx', 'ppt', 'pptx']),
            'is_published' => fake()->boolean(80), // 80% chance of being published
            'views_count' => fake()->numberBetween(0, 1000),
            'downloads_count' => fake()->numberBetween(0, 500),
        ];
    }

    /**
     * Create a published material
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
        ]);
    }

    /**
     * Create an unpublished material
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }

    /**
     * Create a material with file
     */
    public function withFile(): static
    {
        return $this->state(function (array $attributes) {
            Storage::fake('public');
            $file = UploadedFile::fake()->create('document.pdf', 1000);
            $filePath = $file->store('materials', 'public');
            
            return [
                'file' => $filePath,
                'file_size' => 1000,
                'file_type' => 'pdf',
            ];
        });
    }
}
