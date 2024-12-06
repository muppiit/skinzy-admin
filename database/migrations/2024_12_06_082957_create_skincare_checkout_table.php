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
        Schema::create('skincare_checkout', function (Blueprint $table) {
            $table->id('id_checkout');
            $table->unsignedBigInteger('id_history');
            $table->integer('quantity');
            $table->decimal('total_harga', 10, 2);
            $table->timestamps();

            // Foreign key
            $table->foreign('id_history')
                  ->references('history_id')
                  ->on('user_histories')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skincare_checkout');
    }
};
