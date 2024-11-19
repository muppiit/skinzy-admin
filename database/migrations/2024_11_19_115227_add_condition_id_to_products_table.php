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
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('condition_id')->nullable()
                ->constrained('skin_conditions', 'condition_id')
                ->onDelete('cascade'); // Jika skin condition dihapus, produk terkait juga dihapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['condition_id']);
            $table->dropColumn('condition_id');
        });
    }
};
