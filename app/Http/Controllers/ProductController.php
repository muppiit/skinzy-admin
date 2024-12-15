<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SkinCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
            'rating' => 'nullable|numeric|min:0|max:5',
            'condition_id' => 'nullable|exists:skin_conditions,condition_id',
        ]);

        $imagePath = null;

        // Upload gambar ke Cloudinary
        if ($request->hasFile('product_image')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('product_image')->getRealPath(), [
                'folder' => 'product-images',
            ])->getSecurePath();
            $imagePath = $uploadedFileUrl;
        }

        // Simpan produk
        Product::create([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'product_image' => $imagePath,
            'price' => $request->price,
            'stok' => $request->stok,
            'rating' => $request->rating,
            'condition_id' => $request->condition_id,
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
        $request->validate([
            'product_name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stok' => 'required|integer',
            'rating' => 'nullable|numeric|min:0|max:5',
            'condition_id' => 'nullable|exists:skin_conditions,condition_id',
        ]);

        $product = Product::findOrFail($product_id);

        // Upload gambar baru ke Cloudinary jika ada
        if ($request->hasFile('product_image')) {
            // Hapus gambar lama dari Cloudinary jika ada
            if ($product->product_image) {
                $publicId = pathinfo($product->product_image)['filename']; // Mendapatkan public ID dari URL
                Cloudinary::destroy('product-images/' . $publicId);
            }

            $uploadedFileUrl = Cloudinary::upload($request->file('product_image')->getRealPath(), [
                'folder' => 'product-images',
            ])->getSecurePath();
            $product->product_image = $uploadedFileUrl;
        }

        $product->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'stok' => $request->stok,
            'rating' => $request->rating,
            'condition_id' => $request->condition_id,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($product_id)
    {
        $product = Product::findOrFail($product_id);

        // Hapus gambar dari Cloudinary jika ada
        if ($product->product_image) {
            // Mendapatkan public ID dari URL
            $parsedUrl = parse_url($product->product_image);
            $publicId = pathinfo($parsedUrl['path'], PATHINFO_FILENAME);

            // Hapus gambar di folder Cloudinary
            Cloudinary::destroy('product-images/' . $publicId);
        }

        // Hapus data produk
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }



    // /**
    //  * Remove the specified product from storage.
    //  */
    // public function destroy($product_id)
    // {
    //     $product = Product::findOrFail($product_id);

    //     if ($product->product_image && Storage::exists('public/' . $product->product_image)) {
    //         Storage::delete('public/' . $product->product_image);
    //     }

    //     $product->delete();

    //     return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    // }
}
