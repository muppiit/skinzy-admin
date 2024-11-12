<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'profile_image' => null,
            'gender' => 'l', // male
            'age' => 30,
            'level' => 'admin',
            'phone_number' => '1234567890',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'birth_date' => '1993-01-01',
        ]);

        User::create([
            'username' => 'user',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'profile_image' => null,
            'gender' => 'p', // female
            'age' => 25,
            'level' => 'user',
            'phone_number' => '0987654321',
            'first_name' => 'Regular',
            'last_name' => 'User',
            'birth_date' => '1998-02-15',
        ]);
    }
}
