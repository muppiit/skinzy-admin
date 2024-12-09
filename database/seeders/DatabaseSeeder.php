<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class); // Call the UserSeeder
        $this->call(SkinConditionSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(SkinpediaSeeder::class);
        $this->call(userRecomendation::class);
        $this->call(TreatmentSeeder::class);
        $this->call(UserHistorySeeder::class);
        $this->call(SkincareCheckoutSeeder::class);





        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
