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
        Schema::create('skinpedia', function (Blueprint $table) {
            $table->id('id_skinpedia'); // Membuat kolom id_skinpedia sebagai primary key
            $table->string('judul'); // Kolom untuk judul
            $table->text('deskripsi'); // Kolom untuk deskripsi
            $table->string('gambar')->nullable(); // Kolom untuk gambar (nullable agar bisa kosong)
            $table->timestamps(); // Menambahkan kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skinpedia');
    }
};
