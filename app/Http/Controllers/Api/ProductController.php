<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Mengambil produk dan relasi dengan skin condition
        $products = Product::with(['skinCondition'])->get();

        if ($products->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No products found'
            ], 404);
        }

        // Menyusun data untuk mengembalikan id condition dan condition name
        $productsData = $products->map(function ($product) {
            return [
                'product_id' => $product->product_id,
                'product_name' => $product->product_name,
                'description' => $product->description,
                'product_image' => $product->product_image,
                'price' => $product->price,
                'stok' => $product->stok,
                'condition_id' => $product->skinCondition ? $product->skinCondition->condition_id : null,
                'condition_name' => $product->skinCondition ? $product->skinCondition->condition_name : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $productsData
        ]);
    }
}
