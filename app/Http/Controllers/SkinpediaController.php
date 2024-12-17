<?php

namespace App\Http\Controllers;

use App\Models\Skinpedia;
use App\Models\SkinCondition;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class SkinpediaController extends Controller
{
    // Menampilkan semua data Skinpedia
    public function index()
    {
        $skinpedias = Skinpedia::all(); // Ambil semua data Skinpedia
        return view('skinpedia.index', compact('skinpedias')); // Kirim data ke view
    }

    /**
     * Show the form for creating a new Skinpedia entry.
     */
    public function create()
    {
        $skinConditions = SkinCondition::all(); // Ambil semua Skin Conditions
        return view('skinpedia.create', compact('skinConditions')); // Kirim ke view
    }

    /**
     * Store a newly created Skinpedia entry.
     */
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar opsional
        ]);

        $imagePath = null;

        // Upload gambar ke Cloudinary jika ada
        if ($request->hasFile('gambar')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('gambar')->getRealPath(), [
                'folder' => 'skinpedia-images',
            ])->getSecurePath();
            $imagePath = $uploadedFileUrl;
        }

        // Simpan Skinpedia ke database
        Skinpedia::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar' => $imagePath,
        ]);

        return redirect()->route('skinpedia.index')->with('success', 'Skinpedia created successfully.');
    }

    /**
     * Show the form for editing the specified Skinpedia entry.
     */
    public function edit($id_skinpedia)
    {
        $skinpedia = Skinpedia::findOrFail($id_skinpedia); // Ambil data berdasarkan ID
        $skinConditions = SkinCondition::all(); // Ambil semua Skin Conditions
        return view('skinpedia.edit', compact('skinpedia', 'skinConditions')); // Kirim data ke view
    }

    /**
     * Update the specified Skinpedia entry.
     */
    public function update(Request $request, $id_skinpedia)
    {
        // Validasi data
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar opsional
        ]);

        $skinpedia = Skinpedia::findOrFail($id_skinpedia); // Ambil data berdasarkan ID

        // Cek apakah ada gambar yang diupload
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama dari Cloudinary jika ada
            if ($skinpedia->gambar) {
                $parsedUrl = parse_url($skinpedia->gambar);
                $publicId = pathinfo($parsedUrl['path'], PATHINFO_FILENAME);
                Cloudinary::destroy('skinpedia-images/' . $publicId);
            }

            // Upload gambar baru ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('gambar')->getRealPath(), [
                'folder' => 'skinpedia-images',
            ])->getSecurePath();
            $skinpedia->gambar = $uploadedFileUrl;
        }

        // Update Skinpedia
        $skinpedia->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar' => $skinpedia->gambar, // Update gambar jika ada
        ]);

        return redirect()->route('skinpedia.index')->with('success', 'Skinpedia updated successfully.');
    }

    /**
     * Remove the specified Skinpedia entry.
     */
    public function destroy($id_skinpedia)
    {
        $skinpedia = Skinpedia::findOrFail($id_skinpedia);

        // Hapus gambar dari Cloudinary jika ada
        if ($skinpedia->gambar) {
            $parsedUrl = parse_url($skinpedia->gambar);
            $publicId = pathinfo($parsedUrl['path'], PATHINFO_FILENAME);
            Cloudinary::destroy('skinpedia-images/' . $publicId);
        }

        // Hapus data dari database
        $skinpedia->delete();

        return redirect()->route('skinpedia.index')->with('success', 'Skinpedia deleted successfully.');
    }
}
