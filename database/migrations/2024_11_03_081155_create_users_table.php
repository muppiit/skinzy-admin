<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Kolom id otomatis
            $table->string('username')->unique(); // Kolom username, harus unik
            $table->string('email')->unique(); // Kolom email, harus unik
            $table->string('password'); // Kolom password
            $table->string('profile_image')->nullable(); // Kolom gambar profil, bisa kosong
            $table->enum('gender', ['l', 'p']); // Kolom gender dengan opsi 'l' (male), 'p' (female)
            $table->integer('age')->nullable(); // Kolom umur, bisa kosong
            $table->enum('level', ['user', 'admin'])->default('user'); // Kolom level, default 'user'
            $table->string('phone_number')->nullable(); // Kolom nomor telepon, bisa kosong
            $table->string('first_name')->nullable(); // Kolom nama depan, bisa kosong
            $table->string('last_name')->nullable(); // Kolom nama belakang, bisa kosong
            $table->date('birth_date')->nullable(); // Kolom tanggal lahir, bisa kosong
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
