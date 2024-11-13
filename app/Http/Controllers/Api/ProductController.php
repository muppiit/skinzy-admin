<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::select('product_id', 'product_name', 'description', 'product_image', 'price', 'stok')->get();

        if ($products->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No products found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }
}
