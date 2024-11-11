<?php

namespace App\Http\Controllers;

use App\Models\SkinCondition;
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
        return view('skinConditions.create');
    }

    // Menyimpan skin condition yang baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'condition_name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Menyimpan data ke database
        SkinCondition::create([
            'condition_name' => $request->condition_name,
            'description' => $request->description,
        ]);

        return redirect()->route('skinConditions.index')->with('success', 'Skin condition created successfully.');
    }

    // Menampilkan form untuk mengedit skin condition
    public function edit($id)
    {
        $skinCondition = SkinCondition::findOrFail($id);
        return view('skinConditions.edit', compact('skinCondition'));
    }

    // Memperbarui skin condition
    public function update(Request $request, $id)
    {
        $request->validate([
            'condition_name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $skinCondition = SkinCondition::findOrFail($id);
        $skinCondition->update([
            'condition_name' => $request->condition_name,
            'description' => $request->description,
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
