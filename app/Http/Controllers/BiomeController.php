<?php

namespace App\Http\Controllers;

use App\Models\Biome;
use App\Models\Dimension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
                $q->root()->with(['subBiomes', 'mobs']);
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

    private function getAvailableImages()
    {
        return [
            'preset' => collect(File::glob(public_path('images/biomes/*.*')))
                ->map(fn($path) => 'images/biomes/' . basename($path)),
            'uploaded' => collect(Storage::disk('public')->files('biomes'))
                ->map(fn($path) => 'biomes/' . basename($path))
        ];
    }

    public function create(Request $request)
    {
        $dimensions = Dimension::all();
        $parentBiomes = Biome::root()->orderBy('name')->get();
        $selectedParent = $request->parent_id ? Biome::find($request->parent_id) : null;

        $images = $this->getAvailableImages();
        $presetImages = $images['preset'];
        $uploadedImages = $images['uploaded'];

        return view('biomes.create', compact('dimensions', 'parentBiomes', 'selectedParent', 'presetImages', 'uploadedImages'));
    }

    /**
     * Store a newly created biome or sub-biome.
     */
    public function store(\App\Http\Requests\SaveBiomeRequest $request)
    {
        $validated = $request->validated();
        $isSubBiome = !empty($validated['parent_id']);

        if ($isSubBiome) {
            $parent = Biome::findOrFail($validated['parent_id']);
            $validated['dimension_id'] = $parent->dimension_id;
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('biomes', 'public');
        } elseif ($request->filled('existing_image')) {
            $validated['image'] = $request->existing_image;
        }

        $biome = Biome::create($validated);

        $redirect = $isSubBiome
            ? redirect()->route('biomes.show', $biome->parent)
            : redirect()->route('biomes.show', $biome);

        return $redirect->with('success', 'Biome entry deployed successfully.');
    }

    public function edit(Biome $biome)
    {
        $dimensions  = Dimension::all();
        $parentBiomes = Biome::root()->where('id', '!=', $biome->id)->orderBy('name')->get();

        $images = $this->getAvailableImages();
        $presetImages = $images['preset'];
        $uploadedImages = $images['uploaded'];

        return view('biomes.edit', compact('biome', 'dimensions', 'parentBiomes', 'presetImages', 'uploadedImages'));
    }

    /**
     * Update the specified biome in storage.
     */
    public function update(\App\Http\Requests\SaveBiomeRequest $request, Biome $biome)
    {
        $validated = $request->validated();
        $isSubBiome = !empty($validated['parent_id']);

        if ($isSubBiome) {
            $parent = Biome::findOrFail($validated['parent_id']);
            $validated['dimension_id'] = $parent->dimension_id;
        } else {
            $validated['parent_id'] = null;
        }

        if ($request->hasFile('image')) {
            if ($biome->image && !str_starts_with($biome->image, 'images/')) {
                Storage::disk('public')->delete($biome->image);
            }
            $validated['image'] = $request->file('image')->store('biomes', 'public');
        } elseif ($request->filled('existing_image')) {
            $validated['image'] = $request->existing_image;
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
