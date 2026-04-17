<?php

namespace App\Http\Controllers;

use App\Models\Mob;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Mob::with(['category', 'biome.dimension'])->withCount('favoritedBy');

        if (\Illuminate\Support\Facades\Auth::check()) {
            $query->withExists(['favoritedBy as is_favorited' => function($q) {
                $q->where('user_id', \Illuminate\Support\Facades\Auth::id());
            }]);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Sorting Logic
        $sort = $request->query('sort', 'newest');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'health_desc':
                $query->orderByRaw('CAST(health AS UNSIGNED) DESC');
                break;
            case 'health_asc':
                $query->orderByRaw('CAST(health AS UNSIGNED) ASC');
                break;
            case 'damage_desc':
                $query->orderByRaw('CAST(damage AS UNSIGNED) DESC');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $mobs = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        if ($request->ajax()) {
            return view('mobs.partials.mob-grid', compact('mobs', 'categories'))->render();
        }

        return view('mobs.index', compact('mobs', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        $biomes = \App\Models\Biome::with('dimension')->orderBy('name')->get();
        return view('mobs.create', compact('categories', 'biomes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'category_id' => 'required|exists:categories,id',
            'biome_id'    => 'nullable|exists:biomes,id',
            'health'      => 'required',
            'damage'      => 'required',
            'drops'       => 'required',
            'description' => 'required',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('mobs', 'public');
            $data['image'] = $path;
        }

        Mob::create($data);

        return redirect()->route('mobs.index')->with('success', 'Mob created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mob $mob)
    {
        $mob->load(['category', 'biome.dimension']);
        return view('mobs.show', compact('mob'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mob $mob)
    {
        $categories = \App\Models\Category::all();
        $biomes = \App\Models\Biome::with('dimension')->orderBy('name')->get();
        return view('mobs.edit', compact('mob', 'categories', 'biomes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mob $mob)
    {
        $request->validate([
            'name'        => 'required',
            'category_id' => 'required|exists:categories,id',
            'biome_id'    => 'nullable|exists:biomes,id',
            'health'      => 'required',
            'damage'      => 'required',
            'drops'       => 'required',
            'description' => 'required',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($mob->image) {
                Storage::disk('public')->delete($mob->image);
            }
            $path = $request->file('image')->store('mobs', 'public');
            $data['image'] = $path;
        }

        $mob->update($data);

        return redirect()->route('mobs.index')->with('success', 'Mob updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mob $mob)
    {
        if ($mob->image) {
            Storage::disk('public')->delete($mob->image);
        }
        $mob->delete();

        return redirect()->route('mobs.index')->with('success', 'Mob deleted successfully.');
    }

    /**
     * Compare specific mobs side by side.
     */
    public function comparison(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        $mobs = Mob::with(['category', 'biome.dimension'])->whereIn('id', $ids)->get();

        if ($mobs->isEmpty()) {
            return redirect()->route('mobs.index')->with('error', 'Please select mobs to compare.');
        }

        return view('mobs.compare', compact('mobs'));
    }
}
