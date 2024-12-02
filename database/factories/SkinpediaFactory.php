<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skinpedia>
 */
class SkinpediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => $this->faker->sentence, // Menghasilkan kalimat random untuk judul
            'deskripsi' => $this->faker->paragraph, // Menghasilkan paragraf random untuk deskripsi
            'gambar' => $this->faker->imageUrl(640, 480, 'products', true), // URL gambar random
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
