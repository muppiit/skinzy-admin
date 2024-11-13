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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('password'); // Kolom nomor telepon, bisa kosong, setelah kolom password
            $table->string('first_name')->nullable()->after('phone_number'); // Kolom nama depan, bisa kosong, setelah kolom nomor telepon
            $table->string('last_name')->nullable()->after('first_name'); // Kolom nama belakang, bisa kosong, setelah kolom nama depan
            $table->date('birth_date')->nullable()->after('last_name'); // Kolom tanggal lahir, bisa kosong, setelah kolom nama belakang
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'first_name', 'last_name', 'birth_date']); // Hapus kolom jika rollback
        });
    }
};
