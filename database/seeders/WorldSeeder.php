<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Dimensions
        $dimensions = [
            ['name' => 'Overworld', 'description' => 'The starting dimension of Minecraft, filled with diverse life and resources.', 'color_theme' => 'green'],
            ['name' => 'Nether', 'description' => 'A hell-like dimension filled with fire, lava, and dangerous creatures.', 'color_theme' => 'red'],
            ['name' => 'The End', 'description' => 'A dark, desolate dimension that home to the Ender Dragon.', 'color_theme' => 'purple'],
        ];

        foreach ($dimensions as $dim) {
            \App\Models\Dimension::updateOrCreate(['name' => $dim['name']], $dim);
        }

        $overworld = \App\Models\Dimension::where('name', 'Overworld')->first();
        $nether = \App\Models\Dimension::where('name', 'Nether')->first();
        $end = \App\Models\Dimension::where('name', 'The End')->first();

        // 2. Migrate existing Mobs
        $mobs = \App\Models\Mob::all();
        foreach ($mobs as $mob) {
            $biomeName = $mob->biome; // Old string column
            
            if (!$biomeName) continue;

            // Simple logic to determine dimension
            $dimId = $overworld->id;
            $lowerBiome = strtolower($biomeName);
            if (str_contains($lowerBiome, 'nether') || str_contains($lowerBiome, 'soul sand') || str_contains($lowerBiome, 'basalt') || str_contains($lowerBiome, 'crimson') || str_contains($lowerBiome, 'warped')) {
                $dimId = $nether->id;
            } elseif (str_contains($lowerBiome, 'end') || str_contains($lowerBiome, 'void')) {
                $dimId = $end->id;
            }

            $biome = \App\Models\Biome::firstOrCreate(
                ['name' => $biomeName],
                [
                    'dimension_id' => $dimId,
                    'description' => 'Discovered in the ' . $biomeName . ' region.'
                ]
            );

            $mob->update(['biome_id' => $biome->id]);
        }
    }
}
