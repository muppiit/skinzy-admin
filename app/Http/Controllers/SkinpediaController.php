<?php

namespace App\Http\Controllers;

use App\Models\Skinpedia;
use App\Models\SkinCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        // Simpan gambar jika ada
        $imagePath = null;
        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('images', 'public');
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
            // Hapus gambar lama jika ada
            if ($skinpedia->gambar && Storage::exists('public/' . $skinpedia->gambar)) {
                Storage::delete('public/' . $skinpedia->gambar);
            }
            $imagePath = $request->file('gambar')->store('images', 'public');
            $skinpedia->gambar = $imagePath;
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

        // Hapus gambar jika ada
        if ($skinpedia->gambar && Storage::exists('public/' . $skinpedia->gambar)) {
            Storage::delete('public/' . $skinpedia->gambar);
        }

        $skinpedia->delete();

        return redirect()->route('skinpedia.index')->with('success', 'Skinpedia deleted successfully.');
    }
}
