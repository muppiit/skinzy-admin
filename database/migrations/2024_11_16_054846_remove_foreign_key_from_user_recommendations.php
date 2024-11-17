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
        Schema::table('user_recommendations', function (Blueprint $table) {
            // Menghapus foreign key pada kolom id_treatment
            $table->dropForeign(['id_treatment']);
            // Jika perlu, menghapus kolom id_treatment
            $table->dropColumn('id_treatment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_recommendations', function (Blueprint $table) {
            $table->foreignId('id_treatment')->constrained('treatments', 'id_treatment')->onDelete('cascade');
        });
    }
};
