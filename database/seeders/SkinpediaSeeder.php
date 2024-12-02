<?php

namespace Database\Seeders;

use App\Models\Skinpedia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkinpediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Skinpedia::factory()->count(10)->create();
    }
}
