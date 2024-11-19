<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skinpedia;
use Illuminate\Http\Request;

class SkinpediaController extends Controller
{
    public function index()
    {
        $skinpedias = Skinpedia::all();

        return response()->json([
            'status' => 'success',
            'data' => $skinpedias
        ]);
    }
}
