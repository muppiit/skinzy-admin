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
        Schema::create('user_histories', function (Blueprint $table) {
            $table->id('history_id'); // Primary key untuk tabel user_histories
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('gambar_scan'); // Kolom untuk menyimpan path gambar hasil scan
            $table->string('gambar_scan_predicted'); // Kolom untuk menyimpan path gambar hasil scan
            $table->date('detection_date'); // Kolom untuk tanggal scan
            $table->foreignId('recommendation_id')->constrained('user_recommendations', 'recommendation_id')->onDelete('cascade');
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_histories');
    }
};
