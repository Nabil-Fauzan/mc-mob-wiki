<?php

namespace App\Http\Controllers;

use App\Models\Mob;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Mob::with(['category', 'biomes.dimension'])->withCount('favoritedBy');

        if (\Illuminate\Support\Facades\Auth::check()) {
            $query->withExists(['favoritedBy as is_favorited' => function($q) {
                $q->where('user_id', \Illuminate\Support\Facades\Auth::id());
            }]);
        }

        // Basic Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Advanced Loot Search
        if ($request->filled('loot_search')) {
            $query->whereHas('loot', function($q) use ($request) {
                $q->where('item_name', 'like', '%' . $request->loot_search . '%');
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Biome Filter
        if ($request->filled('biome')) {
            $query->whereHas('biomes', function($q) use ($request) {
                $q->where('biomes.id', $request->biome);
            });
        }

        // Combat Behavior Filters
        if ($request->has('is_melee') && $request->is_melee == 'true') {
            $query->where('is_melee', true);
        }
        if ($request->has('is_ranged') && $request->is_ranged == 'true') {
            $query->where('is_ranged', true);
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
                $query->orderByRaw('CAST(health_normal AS UNSIGNED) DESC');
                break;
            case 'damage_desc':
                $query->orderByRaw('CAST(damage_normal AS UNSIGNED) DESC');
                break;
            case 'xp_desc':
                $query->orderByRaw('CAST(xp_reward AS UNSIGNED) DESC');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $mobs = $query->paginate(12)->withQueryString();

        // Semantic Search Fallback for main index
        if ($mobs->isEmpty() && $request->filled('search')) {
            $semanticName = app(\App\Http\Controllers\OracleController::class)->extractSemanticName($request->search);
            if ($semanticName) {
                // Re-build query with semantic name
                $query = Mob::with(['category', 'biomes.dimension'])->withCount('favoritedBy');
                if (\Illuminate\Support\Facades\Auth::check()) {
                    $query->withExists(['favoritedBy as is_favorited' => function($q) {
                        $q->where('user_id', \Illuminate\Support\Facades\Auth::id());
                    }]);
                }
                $query->where('name', 'like', '%' . $semanticName . '%');
                $mobs = $query->paginate(12)->withQueryString();
                
                // Alert the user that this is a semantic match
                if ($mobs->isNotEmpty()) {
                    session()->now('info', "Semantic Match: Menampilkan hasil untuk '{$semanticName}'.");
                }
            }
        }

        $categories = Category::all();
        $allBiomes = \App\Models\Biome::with('dimension')->orderBy('name')->get();

        if ($request->ajax()) {
            return view('mobs.partials.mob-grid', compact('mobs', 'categories'))->render();
        }

        return view('mobs.index', compact('mobs', 'categories', 'allBiomes'));
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
            'biome_ids'   => 'nullable|array',
            'biome_ids.*' => 'exists:biomes,id',
            'health'      => 'nullable',
            'damage'      => 'nullable',
            'drops'       => 'nullable|string',
            'xp_reward'   => 'nullable|string',
            'description' => 'required',
            'spawning_conditions' => 'nullable|string',
            'health_easy'   => 'nullable|string',
            'health_normal' => 'nullable|string',
            'health_hard'   => 'nullable|string',
            'damage_easy'   => 'nullable|string',
            'damage_normal' => 'nullable|string',
            'damage_hard'   => 'nullable|string',
            'melee_attack'  => 'nullable|string',
            'ranged_attack' => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'loot'          => 'nullable|array',
            'loot.*.item_name' => 'required|string',
            'loot.*.quantity'  => 'nullable|string',
            'loot.*.chance'    => 'nullable|string',
            'loot.*.rarity'    => 'nullable|string',
            'loot.*.icon'      => 'nullable|string',
        ]);

        $data = $request->except(['biome_ids', 'loot']);
        
        // Sync base fields for sorting/compatibility
        $data['health'] = $request->health_normal ?: ($request->health_easy ?: ($request->health_hard ?: '0'));
        $data['damage'] = $request->damage_normal ?: ($request->damage_easy ?: ($request->damage_hard ?: '0'));

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('mobs', 'public');
            $data['image'] = $path;
        }

        $mob = Mob::create($data);
        
        if ($request->has('biome_ids')) {
            $mob->biomes()->sync($request->biome_ids);
        }

        if ($request->has('loot')) {
            foreach ($request->loot as $dropData) {
                if (!empty($dropData['item_name'])) {
                    $mob->loot()->create($dropData);
                }
            }
        }

        return redirect()->route('mobs.index')->with('success', 'Mob created successfully.');
    }

    public function show(Mob $mob)
    {
        $mob->load([
            'category',
            'biomes.dimension',
            'loot',
            'comments' => function ($query) {
                $query->with(['user', 'votes'])
                    ->withCount('votes')
                    ->latest();
            },
        ]);

        if (Auth::check()) {
            $mob->comments->each(function ($comment) {
                $comment->is_voted = $comment->votes->contains('user_id', Auth::id());
            });
        }
        
        $oracle = app(\App\Http\Controllers\OracleController::class);
        $relatedMobs = $oracle->getRelatedEntities($mob);
          
        if($relatedMobs->isEmpty()) {
            $biomeIds = $mob->biomes->pluck('id');
            $relatedMobs = Mob::whereHas('biomes', function($q) use ($biomeIds) {
                $q->whereIn('biomes.id', $biomeIds);
            })->where('id', '!=', $mob->id)
              ->with(['category', 'biomes'])
              ->limit(4)
              ->get();
              
            if($relatedMobs->isEmpty()) {
                $relatedMobs = Mob::where('category_id', $mob->category_id)
                    ->where('id', '!=', $mob->id)
                    ->with(['category', 'biomes'])
                    ->limit(4)
                    ->get();
            }
        }

        return view('mobs.show', compact('mob', 'relatedMobs'));
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
            'biome_ids'   => 'nullable|array',
            'biome_ids.*' => 'exists:biomes,id',
            'health'      => 'nullable',
            'damage'      => 'nullable',
            'drops'       => 'nullable|string',
            'xp_reward'   => 'nullable|string',
            'description' => 'required',
            'spawning_conditions' => 'nullable|string',
            'health_easy'   => 'nullable|string',
            'health_normal' => 'nullable|string',
            'health_hard'   => 'nullable|string',
            'damage_easy'   => 'nullable|string',
            'damage_normal' => 'nullable|string',
            'damage_hard'   => 'nullable|string',
            'melee_attack'  => 'nullable|string',
            'ranged_attack' => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'loot'          => 'nullable|array',
            'loot.*.item_name' => 'required|string',
            'loot.*.quantity'  => 'nullable|string',
            'loot.*.chance'    => 'nullable|string',
            'loot.*.rarity'    => 'nullable|string',
            'loot.*.icon'      => 'nullable|string',
        ]);

        $data = $request->except(['biome_ids', 'loot']);
        
        // Sync base fields for sorting/compatibility
        $data['health'] = $request->health_normal ?: ($request->health_easy ?: ($request->health_hard ?: '0'));
        $data['damage'] = $request->damage_normal ?: ($request->damage_easy ?: ($request->damage_hard ?: '0'));

        if ($request->hasFile('image')) {
            if ($mob->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($mob->image);
            }
            $path = $request->file('image')->store('mobs', 'public');
            $data['image'] = $path;
        }

        // Track revisions before saving
        $mob->fill($data);
        foreach ($mob->getDirty() as $field => $newValue) {
            $oldValue = $mob->getOriginal($field);
            if ($oldValue !== $newValue) {
                \App\Models\MobRevision::create([
                    'mob_id' => $mob->id,
                    'user_id' => Auth::id(),
                    'field' => $field,
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                ]);
            }
        }
        $mob->save();
        
        if ($request->has('biome_ids')) {
            $mob->biomes()->sync($request->biome_ids);
        }

        if ($request->has('loot')) {
            $mob->loot()->delete();
            foreach ($request->loot as $dropData) {
                if (!empty($dropData['item_name'])) {
                    $mob->loot()->create($dropData);
                }
            }
        }

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
     * Display the revision history for a specific mob.
     */
    public function history(Mob $mob)
    {
        $revisions = \App\Models\MobRevision::where('mob_id', $mob->id)
            ->with('user')
            ->latest()
            ->get();
            
        return view('mobs.history', compact('mob', 'revisions'));
    }

    /**
     * Revert a specific revision.
     */
    public function revert(Request $request, Mob $mob, \App\Models\MobRevision $revision)
    {
        if ($revision->mob_id !== $mob->id) {
            abort(404);
        }

        // Track this revert as a new revision!
        $field = $revision->field;
        $currentValue = $mob->$field;
        $revertedValue = $revision->old_value;

        \App\Models\MobRevision::create([
            'mob_id' => $mob->id,
            'user_id' => Auth::id(), // Admin doing the revert
            'field' => $field,
            'old_value' => $currentValue,
            'new_value' => $revertedValue,
        ]);

        $mob->$field = $revertedValue;
        $mob->save();

        return back()->with('success', "Reverted {$field} to its previous state.");
    }

    /**
     * Compare specific mobs side by side.
     */
    public function comparison(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        $mobs = Mob::with(['category', 'biomes.dimension'])->whereIn('id', $ids)->get();

        if ($mobs->isEmpty()) {
            return redirect()->route('mobs.index')->with('error', 'Please select mobs to compare.');
        }

        return view('mobs.compare', compact('mobs'));
    }

    /**
     * API Search for live results.
     */
    public function apiSearch(Request $request)
    {
        $query = $request->query('q');
        if (!$query) return response()->json([]);

        $mobs = Mob::with(['category', 'biomes'])
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        if ($mobs->isEmpty()) {
            $semanticName = app(\App\Http\Controllers\OracleController::class)->extractSemanticName($query);
            if ($semanticName) {
                $mobs = Mob::with(['category', 'biomes'])
                    ->where('name', 'like', "%{$semanticName}%")
                    ->limit(5)
                    ->get();
            }
        }

        $mobs = $mobs->map(function($mob) {
                return [
                    'id' => $mob->id,
                    'name' => $mob->name,
                    'category' => $mob->category->name,
                    'habitat' => $mob->biomes->first()->name ?? 'Global',
                    'image' => $mob->image ? asset('storage/' . $mob->image) : null,
                    'url' => route('mobs.show', $mob)
                ];
            });

        return response()->json($mobs);
    }
}
