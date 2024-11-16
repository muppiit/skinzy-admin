<?php

namespace App\Http\Controllers;

use App\Models\SkinCondition;
use App\Models\Treatment; // Tambahkan ini untuk mengambil data treatment
use Illuminate\Http\Request;

class SkinConditionController extends Controller
{
    // Menampilkan semua skin conditions
    public function index()
    {
        $skinConditions = SkinCondition::all();
        return view('skinConditions.index', compact('skinConditions'));
    }

    // Menampilkan form untuk membuat skin condition baru
    public function create()
    {
        $treatments = Treatment::all(); // Ambil semua treatment untuk dropdown
        return view('skinConditions.create', compact('treatments'));
    }

    // Menyimpan skin condition yang baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'condition_name' => 'required|string|max:255',
            'description' => 'required|string',
            'id_treatment' => 'required|exists:treatments,id_treatment', // Validasi treatment
        ]);

        // Menyimpan data ke database
        SkinCondition::create([
            'condition_name' => $request->condition_name,
            'description' => $request->description,
            'id_treatment' => $request->id_treatment, // Tambahkan field ini
        ]);

        return redirect()->route('skinConditions.index')->with('success', 'Skin condition created successfully.');
    }

    // Menampilkan form untuk mengedit skin condition
    public function edit($id)
    {
        $skinCondition = SkinCondition::findOrFail($id);
        $treatments = Treatment::all(); // Ambil semua treatment untuk dropdown
        return view('skinConditions.edit', compact('skinCondition', 'treatments'));
    }

    // Memperbarui skin condition
    public function update(Request $request, $id)
    {
        $request->validate([
            'condition_name' => 'required|string|max:255',
            'description' => 'required|string',
            'id_treatment' => 'required|exists:treatments,id_treatment', // Validasi treatment
        ]);

        $skinCondition = SkinCondition::findOrFail($id);
        $skinCondition->update([
            'condition_name' => $request->condition_name,
            'description' => $request->description,
            'id_treatment' => $request->id_treatment, // Tambahkan field ini
        ]);

        return redirect()->route('skinConditions.index')->with('success', 'Skin condition updated successfully.');
    }

    // Menghapus skin condition
    public function destroy($id)
    {
        $skinCondition = SkinCondition::findOrFail($id);
        $skinCondition->delete();

        return redirect()->route('skinConditions.index')->with('success', 'Skin condition deleted successfully.');
    }
}
