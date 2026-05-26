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
    public function store(\App\Http\Requests\SaveMobRequest $request, \App\Services\MobService $mobService)
    {
        $mobService->saveMob(new Mob(), $request);
        return redirect()->route('mobs.index')->with('success', 'Mob created successfully.');
    }

    public function show(Mob $mob)
    {
        $mob->load([
            'category',
            'biomes.dimension',
            'loot',
            'comments' => function ($query) {
                $query->with('user')
                    ->withCount('votes');
                
                if (Auth::check()) {
                    $query->withExists(['votes as is_voted' => function($q) {
                        $q->where('user_id', Auth::id());
                    }]);
                }
                
                $query->latest();
            },
        ]);

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
    public function update(\App\Http\Requests\SaveMobRequest $request, Mob $mob, \App\Services\MobService $mobService)
    {
        $mobService->saveMob($mob, $request);
        return redirect()->route('mobs.index')->with('success', 'Mob updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mob $mob)
    {
        abort_unless(\Illuminate\Support\Facades\Auth::user()->is_admin, 403, 'Only administrators can delete mobs.');

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
        abort_unless(\Illuminate\Support\Facades\Auth::user()->is_admin, 403, 'Only administrators can revert mob revisions.');

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
