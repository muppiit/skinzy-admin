<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SkinCondition;
use Illuminate\Http\Request;

class SkinConditionController extends Controller
{
    public function index()
    {
        // Mengambil semua data skin conditions
        $conditions = SkinCondition::all(['condition_id', 'condition_name', 'description']);

        // Mengembalikan data dalam format JSON
        return response()->json([
            'status' => 'success',
            'data' => $conditions
        ]);
    }
}
