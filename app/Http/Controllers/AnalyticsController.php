<?php

namespace App\Http\Controllers;

use App\Models\Mob;
use App\Models\Category;
use App\Models\Dimension;
use App\Models\Biome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display a comprehensive dashboard of wiki intelligence.
     */
    public function index()
    {
        // 1. Classification Distribution (Category)
        $categories = Category::withCount('mobs')->get();
        $categoryLabels = $categories->pluck('name');
        $categoryData = $categories->pluck('mobs_count');

        // 2. Ecosystem Density (Mobs per Dimension)
        $dimensions = Dimension::with(['biomes' => function($q) {
            $q->withCount('mobs');
        }])->get();
        
        $dimensionLabels = $dimensions->pluck('name');
        $dimensionData = $dimensions->map(function($dim) {
            return $dim->biomes->sum('mobs_count');
        });

        // 3. Global Vitality (Health/Damage)
        $avgHealth = Mob::avg(DB::raw('CAST(health AS UNSIGNED)'));
        $avgDamage = Mob::avg(DB::raw('CAST(damage AS UNSIGNED)'));

        // 4. Lethality Ranking (Top 5)
        $topDeadly = Mob::with(['category', 'biomes'])
            ->select('*')
            ->selectRaw('(CAST(health AS UNSIGNED) + CAST(damage AS UNSIGNED) * 2) as threat_score')
            ->orderBy('threat_score', 'desc')
            ->limit(5)
            ->get();

        // 5. Total Metrics
        // 6. Top Researchers (Leaderboard)
        $topResearchers = \App\Models\User::all()->map(function($user) {
            $favs = $user->favorite_mobs()->count();
            $coms = $user->comments()->count();
            $xp = ($favs * 125) + ($coms * 350);
            $user->xp = $xp;
            $user->lvl = floor(sqrt($xp / 100)) + 1;
            return $user;
        })->sortByDesc('xp')->take(5);

        // 7. Dimension Stability (Hostile vs Passive Ratio)
        $dimensionStability = $dimensions->map(function($dim) {
            $mobs = Mob::whereHas('biomes', function($q) use ($dim) {
                $q->where('dimension_id', $dim->id);
            })->get();
            
            $hostile = $mobs->filter(fn($m) => $m->category->name == 'Hostile')->count();
            $total = $mobs->count();
            $stability = $total > 0 ? round((($total - $hostile) / $total) * 100) : 100;
            
            return [
                'name' => $dim->name,
                'stability' => $stability,
                'status' => $stability > 70 ? 'Stable' : ($stability > 40 ? 'Volatile' : 'Critical')
            ];
        });

        $totalStats = [
            'mobs' => Mob::count(),
            'biomes' => Biome::count(),
            'dimensions' => Dimension::count(),
            'top_researchers' => $topResearchers,
            'stability' => $dimensionStability
        ];

        return view('pages.stats', compact(
            'categoryLabels', 'categoryData',
            'dimensionLabels', 'dimensionData',
            'avgHealth', 'avgDamage',
            'topDeadly', 'totalStats'
        ));
    }
}
