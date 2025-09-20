<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kelas' => fake()->randomElement(['X', 'XI', 'XII']) . ' ' . fake()->randomElement(['A', 'B', 'C', 'D']),
            'tingkat' => fake()->randomElement(['X', 'XI', 'XII']),
            'jurusan' => fake()->randomElement(['Teknik Informatika', 'Teknik Mesin', 'Teknik Elektro', 'Akuntansi', 'Administrasi Perkantoran']),
            'kapasitas' => fake()->numberBetween(20, 40),
            'wali_kelas' => fake()->name(),
            'tahun_ajaran' => fake()->randomElement(['2023/2024', '2024/2025']),
            'status' => fake()->randomElement(['aktif', 'tidak_aktif']),
        ];
    }

    /**
     * Create an active class
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aktif',
        ]);
    }

    /**
     * Create an inactive class
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'tidak_aktif',
        ]);
    }
}
