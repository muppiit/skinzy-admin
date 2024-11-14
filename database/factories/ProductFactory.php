<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'product_name' => $this->faker->word(), // Nama produk acak
            'description' => $this->faker->sentence(10), // Deskripsi produk dengan kalimat acak
            'product_image' => $this->faker->imageUrl(640, 480, 'products', true), // URL gambar acak untuk produk
            'price' => $this->faker->randomFloat(2, 10, 1000), // Harga acak antara 10 sampai 1000
            'stok' => $this->faker->numberBetween(1, 100), // Stok acak antara 1 sampai 100
        ];
    }
}
