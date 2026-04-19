<?php

namespace App\Http\Controllers;

use App\Models\Dimension;
use App\Models\Mob;
use App\Models\Biome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DimensionController extends Controller
{
    /**
     * Display the Dimension Intelligence Hub.
     */
    public function index()
    {
        $dimensions = Dimension::with('biomes')->get();
        $stats = [];

        foreach ($dimensions as $dim) {
            $mobs = Mob::whereHas('biomes', function($q) use ($dim) {
                $q->where('dimension_id', $dim->id);
            })->with('loot')->get();

            $totalHealth = 0;
            $totalDamage = 0;
            $mobCount = $mobs->count();
            
            $lootItems = [];
            
            foreach ($mobs as $mob) {
                // Extract numeric value from health_normal
                preg_match('/\d+/', $mob->health_normal ?: $mob->health, $hMatches);
                $totalHealth += intval($hMatches[0] ?? 0);

                // Extract numeric value from damage_normal
                preg_match('/\d+/', $mob->damage_normal ?: $mob->damage, $dMatches);
                $totalDamage += intval($dMatches[0] ?? 0);

                // Collect unique loot names
                foreach ($mob->loot as $drop) {
                    $lootItems[] = $drop->item_name;
                }
            }

            $avgHealth = $mobCount > 0 ? $totalHealth / $mobCount : 0;
            $avgDamage = $mobCount > 0 ? $totalDamage / $mobCount : 0;
            
            // Calculate Danger Level (0-100 scale)
            // Weight Damage more than Health as per user preference
            $dangerLevel = ($avgHealth * 0.4 + $avgDamage * 0.6) * 5; 
            $dangerLevel = min(max($dangerLevel, 0), 100);

            // Get Top 3 Guardians (Strongest by combined score)
            $guardians = $mobs->map(function($m) {
                preg_match('/\d+/', $m->health_normal ?: $m->health, $hM);
                preg_match('/\d+/', $m->damage_normal ?: $m->damage, $dM);
                $m->threat_score = intval($hM[0] ?? 0) + (intval($dM[0] ?? 0) * 1.5);
                return $m;
            })->sortByDesc('threat_score')->take(3);

            $stats[$dim->id] = [
                'dimension' => $dim,
                'mob_count' => $mobCount,
                'avg_health' => number_format($avgHealth, 1),
                'avg_damage' => number_format($avgDamage, 1),
                'danger_level' => round($dangerLevel),
                'guardians' => $guardians,
                'unique_loot_count' => count(array_unique($lootItems)),
                'biome_count' => $dim->biomes->count(),
            ];
        }

        return view('dimensions.index', compact('stats', 'dimensions'));
    }
}
