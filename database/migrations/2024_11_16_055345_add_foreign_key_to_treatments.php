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
        Schema::table('skin_conditions', function (Blueprint $table) {
            // Menambahkan kolom foreign key yang mengacu ke tabel treatments
            $table->foreignId('id_treatment')->nullable()
                  ->constrained('treatments', 'id_treatment')
                  ->onDelete('cascade'); // Hapus kondisi kulit jika treatment terkait dihapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skin_conditions', function (Blueprint $table) {
            // Menghapus kolom foreign key
            $table->dropForeign(['id_treatment']);
            $table->dropColumn('id_treatment');
        });
    }
};
