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
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id'); // Kolom product_id otomatis sebagai primary key
            $table->string('product_name'); // Kolom nama produk
            $table->text('description')->nullable(); // Kolom deskripsi produk, bisa kosong
            $table->string('product_image')->nullable(); // Kolom gambar produk, bisa kosong
            $table->decimal('price', 10, 2); // Kolom harga produk dengan dua desimal
            $table->integer('stok'); // Kolom stok produk
            $table->integer('rating'); // Kolom stok produk
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
