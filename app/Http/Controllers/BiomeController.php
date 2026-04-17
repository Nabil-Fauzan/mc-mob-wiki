<?php

namespace App\Http\Controllers;

use App\Models\Biome;
use App\Models\Dimension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BiomeController extends Controller
{
    /**
     * Display a listing of all top-level biomes grouped by dimension.
     */
    public function index()
    {
        // Only load top-level biomes (no parent) with their sub-biomes and mob counts
        $dimensions = Dimension::with([
            'biomes' => function ($q) {
                $q->whereNull('parent_id')->with(['subBiomes', 'mobs']);
            }
        ])->get();

        return view('biomes.index', compact('dimensions'));
    }

    /**
     * Display a specific biome and its mobs and sub-biomes.
     */
    public function show(Biome $biome)
    {
        $biome->load(['mobs.category', 'dimension', 'subBiomes.mobs', 'parent']);
        return view('biomes.show', compact('biome'));
    }

    // -------------------------
    // Admin: Biome CRUD
    // -------------------------

    /**
     * Show form to create a top-level biome OR a sub-biome.
     * Pass ?parent_id=X to pre-select a parent biome.
     */
    public function create(Request $request)
    {
        $dimensions = Dimension::all();
        // Only top-level biomes can be parents
        $parentBiomes = Biome::whereNull('parent_id')->orderBy('name')->get();
        $selectedParent = $request->parent_id ? Biome::find($request->parent_id) : null;

        return view('biomes.create', compact('dimensions', 'parentBiomes', 'selectedParent'));
    }

    /**
     * Store a newly created biome or sub-biome.
     */
    public function store(Request $request)
    {
        $isSubBiome = !empty($request->parent_id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'parent_id'   => 'nullable|exists:biomes,id',
            'dimension_id'=> $isSubBiome ? 'nullable' : 'required|exists:dimensions,id',
            'description' => 'required|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        // Sub-biomes inherit their parent's dimension
        if ($isSubBiome) {
            $parent = Biome::findOrFail($validated['parent_id']);
            $validated['dimension_id'] = $parent->dimension_id;
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('biomes', 'public');
        }

        $biome = Biome::create($validated);

        $redirect = $isSubBiome
            ? redirect()->route('biomes.show', $biome->parent)
            : redirect()->route('biomes.show', $biome);

        return $redirect->with('success', 'Biome entry deployed successfully.');
    }

    /**
     * Show the edit form for a biome or sub-biome.
     */
    public function edit(Biome $biome)
    {
        $dimensions  = Dimension::all();
        // Exclude self from potential parents
        $parentBiomes = Biome::whereNull('parent_id')->where('id', '!=', $biome->id)->orderBy('name')->get();

        return view('biomes.edit', compact('biome', 'dimensions', 'parentBiomes'));
    }

    /**
     * Update the specified biome in storage.
     */
    public function update(Request $request, Biome $biome)
    {
        $isSubBiome = !empty($request->parent_id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'parent_id'   => 'nullable|exists:biomes,id',
            'dimension_id'=> $isSubBiome ? 'nullable' : 'required|exists:dimensions,id',
            'description' => 'required|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($isSubBiome) {
            $parent = Biome::findOrFail($validated['parent_id']);
            $validated['dimension_id'] = $parent->dimension_id;
        } else {
            $validated['parent_id'] = null;
        }

        if ($request->hasFile('image')) {
            if ($biome->image) {
                Storage::disk('public')->delete($biome->image);
            }
            $validated['image'] = $request->file('image')->store('biomes', 'public');
        }

        $biome->update($validated);

        $redirect = $biome->parent_id
            ? redirect()->route('biomes.show', $biome->parent)
            : redirect()->route('biomes.show', $biome);

        return $redirect->with('success', 'Biome Intel updated successfully.');
    }

    /**
     * Remove the specified biome from storage.
     */
    public function destroy(Biome $biome)
    {
        $parentBiome = $biome->parent;

        if ($biome->image) {
            Storage::disk('public')->delete($biome->image);
        }
        $biome->delete();

        $redirect = $parentBiome
            ? redirect()->route('biomes.show', $parentBiome)
            : redirect()->route('biomes.index');

        return $redirect->with('success', 'Biome eliminated from the master registry.');
    }
}
