<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tambahkan admin
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'), // Gantilah dengan password yang aman
            'profile_image' => '', // Gantilah dengan path yang sesuai
            'gender' => 'L', // L = Laki-laki, P = Perempuan
            'age' => 30,
            'level' => 'admin',
        ]);

        // Tambahkan user biasa
        User::create([
            'username' => 'user1',
            'email' => 'kaifu.fid@gmail.com',
            'password' => Hash::make('12345'), // Gantilah dengan password yang aman
            'profile_image' => '', // Gantilah dengan path yang sesuai
            'gender' => 'P', // L = Laki-laki, P = Perempuan
            'age' => 25,
            'level' => 'user',
        ]);
    }
}
