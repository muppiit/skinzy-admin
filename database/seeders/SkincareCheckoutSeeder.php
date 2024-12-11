<?php

namespace Database\Seeders;

use App\Models\SkincareCheckout;
use Illuminate\Database\Seeder;

class SkincareCheckoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SkincareCheckout::create([
            'id_history' => 1, // Mengacu pada UserHistory dengan ID 1
            'quantity' => 2,
            'total_harga' => 500000, // Total harga dalam satuan tertentu (contoh: Rupiah)
        ]);

        SkincareCheckout::create([
            'id_history' => 2, // Mengacu pada UserHistory dengan ID 2
            'quantity' => 1,
            'total_harga' => 250000, // Total harga dalam satuan tertentu (contoh: Rupiah)
        ]);
    }
}
