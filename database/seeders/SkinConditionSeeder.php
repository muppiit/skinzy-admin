<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SkinCondition;

class SkinConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menambahkan data contoh untuk SkinCondition
        SkinCondition::create([
            'condition_name' => 'Rendah',
            'description' => 'A mild skin condition with minimal symptoms.',
        ]);

        SkinCondition::create([
            'condition_name' => 'Sedang',
            'description' => 'A moderate skin condition with noticeable symptoms.',
        ]);

        SkinCondition::create([
            'condition_name' => 'Parah',
            'description' => 'A severe skin condition with intense symptoms.',
        ]);

        SkinCondition::create([
            'condition_name' => 'Sangat Parah',
            'description' => 'An extreme skin condition with very intense symptoms.',
        ]);
    }
}
