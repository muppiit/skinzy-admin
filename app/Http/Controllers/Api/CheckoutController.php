<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SkincareCheckout;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    
    public function checkout(Request $request)
    {
        try {
            // Mendapatkan user yang sedang login berdasarkan JWT token
            $user = auth()->user();

            // Validasi input
            $validated = $request->validate([
                'history_id' => 'required|exists:user_histories,history_id',
                'products' => 'required|array',
                'products.*.product_id' => 'required|exists:products,product_id',
                'products.*.quantity' => 'required|integer|min:1'
            ]);

            $historyId = $validated['history_id'];
            $products = $validated['products'];

            $totalHarga = 0;
            $checkoutProducts = [];

            // Loop melalui produk untuk menghitung total harga dan mempersiapkan data checkout
            foreach ($products as $product) {
                $productData = Product::findOrFail($product['product_id']);
                $hargaDasar = $productData->price;
                $jumlah = $product['quantity'];

                $totalHarga += $hargaDasar * $jumlah;

                $checkoutProducts[] = [
                    'product_id' => $productData->product_id,
                    'product_name' => $productData->product_name,
                    'price' => $hargaDasar,
                    'quantity' => $jumlah
                ];
            }

            // Membuat data checkout
            $checkout = SkincareCheckout::create([
                'id_history' => $historyId,
                'quantity' => array_sum(array_column($products, 'quantity')),
                'total_harga' => $totalHarga
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Checkout berhasil dibuat.',
                'data' => [
                    'id_checkout' => $checkout->id_checkout,
                    'id_user' => $user->id,
                    'id_history' => $checkout->id_history,
                    'total_harga' => $checkout->total_harga,
                    'products' => $checkoutProducts,
                    'created_at' => $checkout->created_at
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat membuat checkout.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
