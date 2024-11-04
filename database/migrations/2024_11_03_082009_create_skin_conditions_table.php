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
        Schema::create('skin_conditions', function (Blueprint $table) {
            $table->id('condition_id'); // Kolom condition_id otomatis sebagai primary key
            $table->string('condition_name'); // Kolom nama kondisi kulit
            $table->text('description')->nullable(); // Kolom deskripsi kondisi kulit, bisa kosong
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skin_conditions');
    }
};
