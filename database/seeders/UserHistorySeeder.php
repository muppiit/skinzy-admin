<?php

namespace Database\Seeders;

use App\Models\UserHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserHistory::create([
            'user_id' => 1, // Mengacu pada User dengan ID 1
            'gambar_scan' => 'gambar scan',
            'gambar_scan_predicted' => 'gambar yolo',
            'detection_date' => '2024-12-01',
            'recommendation_id' => 1, // Mengacu pada UserRecommendation dengan ID 1
        ]);

        UserHistory::create([
            'user_id' => 2, // Mengacu pada User dengan ID 2
            'gambar_scan' => 'gambar scan',
            'gambar_scan_predicted' => 'gambar yolo',
            'detection_date' => '2024-12-02',
            'recommendation_id' => 2, // Mengacu pada UserRecommendation dengan ID 2
        ]);
    }
}
