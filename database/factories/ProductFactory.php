<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\SkinCondition; // Add this import for SkinCondition model
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        // List of realistic product names
        $productNames = [
            'Skintific', 'Glad2Glow', 'Cetaphil', 'Neutrogena', 'The Ordinary', 'CeraVe', 'Olay', 
            'La Roche-Posay', 'Eucerin', 'Aveeno', 'Vichy', 'Kiehl\'s', 'Bioderma', 'Dr. Dennis Gross'
        ];

        // Check if there are any conditions in the skin_conditions table
        $condition = SkinCondition::inRandomOrder()->first();

        // If there is no condition, set a default value or handle the error
        if (!$condition) {
            throw new \Exception("No skin condition found in the database.");
        }

        // Get the valid condition_id from the existing skin_condition record
        $condition_id = $condition->id;

        return [
            'product_name' => $this->faker->randomElement($productNames), // Random product name from the list
            'description' => $this->faker->sentence(10), // Deskripsi produk dengan kalimat acak
            'product_image' => $this->faker->imageUrl(640, 480, 'products', true), // URL gambar acak untuk produk
            'price' => $this->faker->randomFloat(2, 10, 1000), // Harga acak antara 10 sampai 1000
            'stok' => $this->faker->numberBetween(1, 100), // Stok acak antara 1 sampai 100
            'rating' => $this->faker->numberBetween(1, 5), // Rating acak antara 1 dan 5 (integer)
            'condition_id' => $condition_id, // Ensure valid condition_id
        ];
    }
}
