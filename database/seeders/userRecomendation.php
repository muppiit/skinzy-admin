<?php

namespace Database\Seeders;

use App\Models\UserRecommendation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class userRecomendation extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserRecommendation::create([
            'condition_id' => 1, // Rendah
        ]);

        UserRecommendation::create([
            'condition_id' => 2, // Sedang
        ]);

        UserRecommendation::create([
            'condition_id' => 3, // Parah
        ]);

        UserRecommendation::create([
            'condition_id' => 4, // Sangat Parah
        ]);
    }
}
