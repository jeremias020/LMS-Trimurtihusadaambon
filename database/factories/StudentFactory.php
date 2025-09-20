<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->siswa(),
            'nis' => fake()->unique()->numerify('########'),
            'nisn' => fake()->unique()->numerify('##########'),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->date('Y-m-d', '2005-01-01'),
            'alamat' => fake()->address(),
            'no_telepon' => fake()->phoneNumber(),
            'kelas_id' => Kelas::factory(),
            'major' => fake()->randomElement(['Teknik Informatika', 'Teknik Mesin', 'Teknik Elektro', 'Akuntansi', 'Administrasi Perkantoran']),
            'tahun_ajaran' => fake()->randomElement(['2023/2024', '2024/2025']),
            'nama_ortu' => fake()->name(),
            'no_telepon_ortu' => fake()->phoneNumber(),
            'golongan_darah' => fake()->randomElement(['A', 'B', 'AB', 'O']),
            'riwayat_penyakit' => fake()->optional()->sentence(),
            'alergi' => fake()->optional()->sentence(),
            'info_kesehatan' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(['aktif', 'lulus', 'pindah', 'dropout']),
        ];
    }

    /**
     * Create an active student
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aktif',
        ]);
    }

    /**
     * Create a graduated student
     */
    public function graduated(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'lulus',
        ]);
    }
}
