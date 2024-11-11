<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    // Menampilkan daftar semua treatment
    public function index()
    {
        $treatments = Treatment::all();
        return view('treatments.index', compact('treatments'));
    }

    // Menampilkan form untuk menambah treatment baru
    public function create()
    {
        return view('treatments.create');
    }

    // Menyimpan treatment yang baru ditambahkan
    public function store(Request $request)
    {
        $request->validate([
            'deskripsi_treatment' => 'required|string',
        ]);

        Treatment::create([
            'deskripsi_treatment' => $request->deskripsi_treatment,
        ]);

        return redirect()->route('treatments.index')->with('success', 'Treatment created successfully.');
    }

    // Menampilkan form untuk mengedit treatment
    public function edit($id)
    {
        $treatment = Treatment::findOrFail($id);
        return view('treatments.edit', compact('treatment'));
    }

    // Mengupdate treatment yang sudah ada
    public function update(Request $request, $id)
    {
        $request->validate([
            'deskripsi_treatment' => 'required|string|max:255',
        ]);

        $treatment = Treatment::findOrFail($id);
        $treatment->update([
            'deskripsi_treatment' => $request->deskripsi_treatment,
        ]);

        return redirect()->route('treatments.index')->with('success', 'Treatment updated successfully.');
    }

    // Menghapus treatment
    public function destroy($id)
    {
        $treatment = Treatment::findOrFail($id);
        $treatment->delete();

        return redirect()->route('treatments.index')->with('success', 'Treatment deleted successfully.');
    }
}
