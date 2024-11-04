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
        Schema::create('user_recommendations', function (Blueprint $table) {
            $table->id('recommendation_id'); // Primary key untuk tabel user_recommendations
            $table->foreignId('condition_id')->constrained('skin_conditions', 'condition_id')->onDelete('cascade'); 
            $table->foreignId('product_id')->constrained('products', 'product_id')->onDelete('cascade'); 
            $table->foreignId('id_treatment')->constrained('treatments', 'id_treatment')->onDelete('cascade'); 
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_recommendations');
    }
};
