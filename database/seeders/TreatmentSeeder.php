<?php

namespace Database\Seeders;

use App\Models\Treatment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Treatment::create([
            'deskripsi_treatment' => 'Menggunakan pelembap ringan setiap hari untuk menjaga kelembapan kulit.',
        ]);

        Treatment::create([
            'deskripsi_treatment' => 'Mengaplikasikan sunscreen dengan SPF 50 setiap pagi untuk melindungi kulit dari paparan UV.',
        ]);

        Treatment::create([
            'deskripsi_treatment' => 'Menggunakan produk eksfoliasi ringan seminggu sekali untuk mengangkat sel kulit mati.',
        ]);

        Treatment::create([
            'deskripsi_treatment' => 'Menggunakan masker wajah berbahan alami untuk menutrisi kulit secara mendalam.',
        ]);
    }
}
