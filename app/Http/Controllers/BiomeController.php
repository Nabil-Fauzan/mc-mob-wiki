<?php

namespace App\Http\Controllers;

use App\Models\Biome;
use App\Models\Dimension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BiomeController extends Controller
{
    /**
     * Show the form for creating a new biome (Admin Only).
     */
    public function create()
    {
        $dimensions = Dimension::all();
        return view('biomes.create', compact('dimensions'));
    }

    /**
     * Store a newly created biome in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dimension_id' => 'required|exists:dimensions,id',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('biomes', 'public');
        }

        Biome::create($validated);

        return redirect()->route('biomes.index')->with('success', 'New biome deployed successfully.');
    }
    /**
     * Show the form for editing the specified biome (Admin Only).
     */
    public function edit(Biome $biome)
    {
        $dimensions = Dimension::all();
        return view('biomes.edit', compact('biome', 'dimensions'));
    }

    /**
     * Update the specified biome in storage.
     */
    public function update(Request $request, Biome $biome)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dimension_id' => 'required|exists:dimensions,id',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($biome->image) {
                Storage::disk('public')->delete($biome->image);
            }
            $validated['image'] = $request->file('image')->store('biomes', 'public');
        }

        $biome->update($validated);

        return redirect()->route('biomes.show', $biome)->with('success', 'Biome Intel updated successfully.');
    }

    /**
     * Remove the specified biome from storage.
     */
    public function destroy(Biome $biome)
    {
        if ($biome->image) {
            Storage::disk('public')->delete($biome->image);
        }
        $biome->delete();

        return redirect()->route('biomes.index')->with('success', 'Biome eliminated from the master registry.');
    }

    /**
     * Display a listing of all biomes.
     */
    public function index()
    {
        $dimensions = Dimension::with('biomes.mobs')->get();
        return view('biomes.index', compact('dimensions'));
    }

    /**
     * Display a specific biome and its mobs.
     */
    public function show(Biome $biome)
    {
        $biome->load(['mobs.category', 'dimension']);
        return view('biomes.show', compact('biome'));
    }
}
