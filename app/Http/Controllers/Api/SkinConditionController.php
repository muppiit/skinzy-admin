<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SkinCondition;
use Illuminate\Http\Request;

class SkinConditionController extends Controller
{
    public function index()
    {
        // Mengambil semua data skin conditions beserta treatment yang terkait
        $conditions = SkinCondition::with('treatment')->get();

        // Mengubah format data skin condition beserta deskripsi treatment yang terkait
        $conditionsData = $conditions->map(function ($condition) {
            return [
                'condition_id' => $condition->condition_id,
                'condition_name' => $condition->condition_name,
                'treatment_description' => $condition->treatment ? $condition->treatment->deskripsi_treatment : null, // Menampilkan deskripsi treatment
            ];
        });

        // Mengembalikan data dalam format JSON
        return response()->json([
            'status' => 'success',
            'data' => $conditionsData
        ]);
    }
}
