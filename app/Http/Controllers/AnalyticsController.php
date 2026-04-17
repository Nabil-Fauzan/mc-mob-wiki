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
        $topDeadly = Mob::with(['category', 'biome'])
            ->select('*')
            ->selectRaw('(CAST(health AS UNSIGNED) + CAST(damage AS UNSIGNED) * 2) as threat_score')
            ->orderBy('threat_score', 'desc')
            ->limit(5)
            ->get();

        // 5. Total Metrics
        $totalStats = [
            'mobs' => Mob::count(),
            'biomes' => Biome::count(),
            'dimensions' => Dimension::count(),
        ];

        return view('pages.stats', compact(
            'categoryLabels', 'categoryData',
            'dimensionLabels', 'dimensionData',
            'avgHealth', 'avgDamage',
            'topDeadly', 'totalStats'
        ));
    }
}
