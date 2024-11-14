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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id('id_treatment'); // Kolom id_treatment otomatis sebagai primary key
            $table->foreignId('condition_id')->constrained('skin_conditions', column: 'condition_id')->onDelete('cascade'); 

            $table->text('deskripsi_treatment'); // Kolom deskripsi treatment
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
