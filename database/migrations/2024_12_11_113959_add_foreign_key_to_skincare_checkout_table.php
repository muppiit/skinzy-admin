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
        Schema::table('skincare_checkout', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->after('id_history'); // Tambahkan kolom product_id

            // Tambahkan foreign key
            $table->foreign('product_id')
                  ->references('product_id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skincare_checkout', function (Blueprint $table) {
            $table->dropForeign(['product_id']); // Hapus foreign key
            $table->dropColumn('product_id');    // Hapus kolom product_id
        });
    }
};
