<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index()
    {
        // Mengambil semua data treatment dengan kolom tertentu
        $treatments = Treatment::all();

        // Mengembalikan data dalam format JSON
        return response()->json([
            'status' => 'success',
            'data' => $treatments
        ]);
    }
}
