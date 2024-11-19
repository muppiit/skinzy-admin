<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SkinCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('skinCondition')->get(); // Ambil produk beserta skin condition terkait
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $skinConditions = SkinCondition::all(); // Ambil semua skin condition
        return view('products.create', compact('skinConditions')); // Kirim ke view
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
            'condition_id' => 'nullable|exists:skin_conditions,condition_id', // Validasi skin condition
        ]);

        // Upload gambar menggunakan Storage
        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('images', 'public');
        }

        // Simpan produk
        Product::create([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'product_image' => $imagePath,
            'price' => $request->price,
            'stok' => $request->stok,
            'condition_id' => $request->condition_id, // Simpan skin condition terkait
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($product_id)
    {
        $product = Product::findOrFail($product_id);
        $skinConditions = SkinCondition::all(); // Ambil semua skin condition
        return view('products.edit', compact('product', 'skinConditions')); // Kirim data ke view
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
            'condition_id' => 'nullable|exists:skin_conditions,condition_id', // Validasi skin condition
        ]);

        $product = Product::findOrFail($product_id);

        // Cek apakah ada gambar yang diupload
        if ($request->hasFile('product_image')) {
            if ($product->product_image && Storage::exists('public/' . $product->product_image)) {
                Storage::delete('public/' . $product->product_image);
            }
            $imagePath = $request->file('product_image')->store('images', 'public');
            $product->product_image = $imagePath;
        }

        // Update produk
        $product->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'stok' => $request->stok,
            'condition_id' => $request->condition_id, // Update skin condition terkait
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($product_id)
    {
        $product = Product::findOrFail($product_id);

        if ($product->product_image && Storage::exists('public/' . $product->product_image)) {
            Storage::delete('public/' . $product->product_image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
