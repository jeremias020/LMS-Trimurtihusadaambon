<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guru>
 */
class GuruFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->guru(),
            'nip' => fake()->unique()->numerify('##########'),
            'nama' => fake()->name(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->date('Y-m-d', '1980-01-01'),
            'alamat' => fake()->address(),
            'no_telepon' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'mata_pelajaran' => fake()->randomElement([
                'Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Fisika', 
                'Kimia', 'Biologi', 'Sejarah', 'Geografi', 'Ekonomi', 'Sosiologi'
            ]),
            'pendidikan_terakhir' => fake()->randomElement(['S1', 'S2', 'S3']),
            'foto' => null,
            'status' => fake()->randomElement(['aktif', 'pensiun', 'pindah']),
        ];
    }

    /**
     * Create an active guru
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aktif',
        ]);
    }

    /**
     * Create a retired guru
     */
    public function retired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pensiun',
        ]);
    }
}
