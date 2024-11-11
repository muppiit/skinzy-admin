<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Pastikan sudah di-import

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all(); // Ambil semua produk dari database
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('products.create'); // Tampilkan form create
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'product_name' => 'required',
            'description' => 'required',
            'product_image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'price' => 'required|numeric',
            'stok' => 'required|integer',
        ]);

        // Upload gambar menggunakan Storage
        if ($request->hasFile('product_image')) {
            // Simpan gambar di storage dan dapatkan path-nya
            $imagePath = $request->file('product_image')->store('images', 'public');
        }

        // Simpan produk
        Product::create([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'product_image' => $imagePath, // Simpan path gambar
            'price' => $request->price,
            'stok' => $request->stok,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($product_id)
    {
        $product = Product::findOrFail($product_id); // Ambil produk berdasarkan ID
        return view('products.edit', compact('product')); // Tampilkan form edit
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $product_id)
    {
        // Validasi data
        $request->validate([
            'product_name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stok' => 'required|integer',
        ]);

        $product = Product::findOrFail($product_id);

        // Cek apakah ada gambar yang diupload
        if ($request->hasFile('product_image')) {
            // Hapus gambar lama jika ada
            if ($product->product_image && Storage::exists('public/' . $product->product_image)) {
                Storage::delete('public/' . $product->product_image);
            }

            // Simpan gambar baru di storage
            $imagePath = $request->file('product_image')->store('images', 'public');
            $product->product_image = $imagePath; // Update gambar
        }

        // Update produk
        $product->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'stok' => $request->stok,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($product_id)
    {
        $product = Product::findOrFail($product_id);

        // Hapus gambar terkait produk jika ada
        if ($product->product_image && Storage::exists('public/' . $product->product_image)) {
            Storage::delete('public/' . $product->product_image);
        }

        $product->delete(); // Hapus produk

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
