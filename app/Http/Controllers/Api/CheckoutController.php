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
                'product_id' => 'required|exists:products,product_id',
                'quantity' => 'required|integer|min:1'
            ]);

            $historyId = $validated['history_id'];
            $productId = $validated['product_id'];
            $quantity = $validated['quantity'];

            // Mendapatkan data produk
            $product = Product::findOrFail($productId);

            // Validasi stok produk
            if ($product->stok < $quantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stok produk tidak mencukupi.',
                ], 422);
            }

            // Hitung total harga
            $totalHarga = $product->price * $quantity;

            // Kurangi stok produk
            $product->stok -= $quantity;
            $product->save();

            // Membuat data checkout
            $checkout = SkincareCheckout::create([
                'id_history' => $historyId,
                'product_id' => $productId,
                'quantity' => $quantity,
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
                    'product' => [
                        'product_id' => $product->product_id,
                        'product_name' => $product->product_name,
                        'price' => $product->price,
                        'quantity' => $quantity
                    ],
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

    public function getUserCheckouts(Request $request)
    {
        try {
            // Mendapatkan user yang sedang login berdasarkan JWT token
            $user = auth()->user();

            // Mengambil semua data checkout yang terkait dengan user dan memuat relasi produk
            $checkouts = SkincareCheckout::with('product')  // Eager load the product relationship
                ->whereHas('userHistory', function ($query) use ($user) {
                    $query->where('user_id', $user->id);  // Pastikan hubungan 'user_id' pada tabel 'user_histories'
                })
                ->get();

            // Menyusun respon dengan data checkout yang ditemukan
            return response()->json([
                'status' => 'success',
                'message' => 'Checkout history retrieved successfully.',
                'data' => $checkouts->map(function ($checkout) {
                    return [
                        'id_checkout' => $checkout->id_checkout,
                        'id_user' => $checkout->userHistory->user_id,  // Assuming 'user_id' exists in the user_histories table
                        'id_history' => $checkout->id_history,
                        'total_harga' => $checkout->total_harga,
                        'product' => [
                            'product_id' => $checkout->product->product_id,
                            'product_name' => $checkout->product->product_name,
                            'price' => $checkout->product->price,
                            'quantity' => $checkout->quantity
                        ],
                        'created_at' => $checkout->created_at
                    ];
                })
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil riwayat checkout.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
