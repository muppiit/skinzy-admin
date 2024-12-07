<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SkincareCheckout;
use App\Models\UserHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        // Validasi input dari request
        $validated = $request->validate([
            'history_id' => 'required|exists:user_histories,history_id',
            'products' => 'required|array', // Array of products
            'products.*.product_id' => 'required|exists:products,product_id',
            'products.*.quantity' => 'required|integer|min:1'
        ]);

        // Ambil data history yang dipilih
        $userHistory = UserHistory::find($validated['history_id']);

        // Inisialisasi total harga keseluruhan checkout
        $totalHargaCheckout = 0;
        $checkoutItems = [];

        // Loop melalui produk yang dipilih
        foreach ($validated['products'] as $productData) {
            $product = Product::find($productData['product_id']);

            // Hitung total harga untuk produk ini
            $totalHarga = $product->price * $productData['quantity'];
            $totalHargaCheckout += $totalHarga;

            // Kurangi stok produk
            if ($product->stok < $productData['quantity']) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Not enough stock for product ID {$product->product_id}",
                ], 400);
            }
            $product->stok -= $productData['quantity'];
            $product->save();

            // Simpan data checkout untuk setiap produk
            $checkoutItem = SkincareCheckout::create([
                'id_history' => $validated['history_id'],
                'quantity' => $productData['quantity'],
                'total_harga' => $totalHarga,
            ]);

            // Tambahkan produk yang dipilih ke array hasil
            $checkoutItems[] = [
                'id_checkout' => $checkoutItem->id_checkout,
                'id_history' => $validated['history_id'],
                'id_product' => $product->product_id,
                'product_name' => $product->product_name,
                'harga' => $product->price,
                'quantity' => $productData['quantity']
            ];
        }

        // Return response
        return response()->json([
            'status' => 'success',
            'message' => 'Checkout successful',
            'products' => $checkoutItems,
            'total_harga_checkout' => $totalHargaCheckout
        ], 201);
    }

    public function getSkincareCheckout(Request $request)
    {
        $userId = Auth::user()->id;

        // Ambil semua history_id untuk user yang sedang login
        $userHistoryIds = \App\Models\UserHistory::where('user_id', $userId)->pluck('history_id');

        // Ambil semua checkout berdasarkan history_id yang cocok dengan user login dan join dengan tabel produk
        $userCheckouts = \App\Models\SkincareCheckout::whereIn('id_history', $userHistoryIds)
            ->join('products', 'skincare_checkout.id_history', '=', 'products.product_id')
            ->select(
                'skincare_checkout.id_checkout',
                'skincare_checkout.id_history',
                'skincare_checkout.quantity',
                'products.product_id',
                'products.product_name',
                'products.product_image',
                'skincare_checkout.total_harga'
            )
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $userCheckouts
        ]);
    }
}
