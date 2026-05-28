<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mob;
use App\Models\Biome;

class MobBiomeSeeder extends Seeder
{
    public function run(): void
    {
        $biomes = Biome::all();
        
        $netherBiomes = $biomes->filter(fn($b) => in_array($b->name, ['Nether wastes', 'Soul sand valley', 'Crimson forest', 'Warped forest', 'Basalt deltas']));
        $endBiomes = $biomes->filter(fn($b) => str_contains(strtolower($b->name), 'end'));
        $oceanBiomes = $biomes->filter(fn($b) => str_contains(strtolower($b->name), 'ocean') || str_contains(strtolower($b->name), 'river') || str_contains(strtolower($b->name), 'beach'));
        $snowBiomes = $biomes->filter(fn($b) => str_contains(strtolower($b->name), 'snow') || str_contains(strtolower($b->name), 'ice') || str_contains(strtolower($b->name), 'frozen'));
        $desertBiomes = $biomes->filter(fn($b) => str_contains(strtolower($b->name), 'desert') || str_contains(strtolower($b->name), 'badlands'));
        $jungleBiomes = $biomes->filter(fn($b) => str_contains(strtolower($b->name), 'jungle') || str_contains(strtolower($b->name), 'bamboo'));
        $forestBiomes = $biomes->filter(fn($b) => str_contains(strtolower($b->name), 'forest') || str_contains(strtolower($b->name), 'grove') || str_contains(strtolower($b->name), 'taiga'));
        $overworldCommon = $biomes->filter(fn($b) => !$netherBiomes->contains($b) && !$endBiomes->contains($b));
        
        $mappings = [
            'Warden' => ['Deep Dark'],
            'Ender Dragon' => ['The End'],
            'Wither' => ['Nether wastes'], // Summoned anywhere, but let's associate with Nether
            'Elder Guardian' => ['Deep ocean', 'Deep lukewarm ocean', 'Deep cold ocean', 'Deep frozen ocean'],
            'Guardian' => ['Deep ocean', 'Deep lukewarm ocean', 'Deep cold ocean', 'Deep frozen ocean'],
            'Enderman' => array_merge($endBiomes->pluck('name')->toArray(), ['Warped forest', 'Nether wastes']),
            'Shulker' => ['End Highlands', 'End Midlands', 'End barrens\u200c [JE only]', 'Small End Islands'],
            'Endermite' => ['The End'],
            'Piglin' => ['Crimson forest', 'Nether wastes'],
            'Piglin Brute' => ['Crimson forest', 'Nether wastes'],
            'Hoglin' => ['Crimson forest'],
            'Zombified Piglin' => ['Nether wastes', 'Crimson forest'],
            'Blaze' => ['Nether wastes'],
            'Wither Skeleton' => ['Nether wastes'],
            'Magma Cube' => ['Basalt deltas', 'Nether wastes'],
            'Ghast' => ['Soul sand valley', 'Nether wastes', 'Basalt deltas'],
            'Strider' => $netherBiomes->pluck('name')->toArray(),
            'Stray' => $snowBiomes->pluck('name')->toArray(),
            'Polar Bear' => $snowBiomes->pluck('name')->toArray(),
            'Snow Golem' => $snowBiomes->pluck('name')->toArray(),
            'Husk' => $desertBiomes->pluck('name')->toArray(),
            'Camel' => ['Desert'],
            'Drowned' => $oceanBiomes->pluck('name')->toArray(),
            'Turtle' => $oceanBiomes->pluck('name')->toArray(),
            'Salmon' => $oceanBiomes->pluck('name')->toArray(),
            'Squid' => $oceanBiomes->pluck('name')->toArray(),
            'Axolotl' => ['Lush Caves'],
            'Ocelot' => $jungleBiomes->pluck('name')->toArray(),
            'Parrot' => $jungleBiomes->pluck('name')->toArray(),
            'Panda' => $jungleBiomes->pluck('name')->toArray(),
            'Silverfish' => ['Windswept hills', 'Windswept gravelly hills', 'Stony peaks'],
            'Mooshroom' => ['Mushroom fields'],
            'Fox' => ['Taiga', 'Snowy Taiga', 'Old Growth Pine Taiga', 'Old Growth Spruce Taiga', 'Grove'],
            'Wolf' => ['Forest', 'Taiga', 'Old Growth Pine Taiga', 'Old Growth Spruce Taiga', 'Grove', 'Snowy Taiga'],
            'Goat' => ['Jagged peaks', 'Frozen peaks', 'Stony peaks', 'Snowy slopes'],
            'Evoker' => ['Dark Forest'],
            'Vindicator' => ['Dark Forest'],
            'Illusioner' => ['Dark Forest'],
            'Pillager' => ['Plains', 'Desert', 'Savanna', 'Snowy Plains', 'Taiga', 'Meadow', 'Grove'],
            'Ravager' => ['Plains', 'Desert', 'Savanna', 'Snowy Plains', 'Taiga', 'Meadow', 'Grove'],
            'Slime' => ['Swamp', 'Mangrove swamp'],
            'Frog' => ['Swamp', 'Mangrove swamp'],
            'Tadpole' => ['Swamp', 'Mangrove swamp'],
            'Bee' => ['Plains', 'Sunflower Plains', 'Flower Forest', 'Forest', 'Birch Forest', 'Meadow', 'Cherry grove'],
            'Allay' => ['Dark Forest', 'Plains'],
            'Cat' => ['Plains', 'Desert', 'Savanna', 'Taiga', 'Snowy Plains'],
            'Iron Golem' => ['Plains', 'Desert', 'Savanna', 'Taiga', 'Snowy Plains'],
            'Villager' => ['Plains', 'Desert', 'Savanna', 'Taiga', 'Snowy Plains'],
            'Zombie Villager' => ['Plains', 'Desert', 'Savanna', 'Taiga', 'Snowy Plains'],
        ];

        // Mobs that spawn almost anywhere in the overworld in darkness
        $overworldHostiles = ['Zombie', 'Skeleton', 'Creeper', 'Spider', 'Cave Spider', 'Witch', 'Phantom', 'Bat'];
        // Passive farm animals
        $overworldAnimals = ['Cow', 'Pig', 'Sheep', 'Chicken', 'Horse', 'Donkey', 'Mule', 'Rabbit', 'Llama', 'Trader Llama'];

        foreach (Mob::all() as $mob) {
            $assignedBiomes = [];
            
            if (isset($mappings[$mob->name])) {
                $assignedBiomes = $biomes->whereIn('name', $mappings[$mob->name])->pluck('id')->toArray();
            } elseif (in_array($mob->name, $overworldHostiles)) {
                $assignedBiomes = $overworldCommon->pluck('id')->toArray();
            } elseif (in_array($mob->name, $overworldAnimals)) {
                // Animals mostly spawn in grassy areas, exclude deep oceans/caves
                $grassy = $overworldCommon->filter(fn($b) => !str_contains(strtolower($b->name), 'ocean') && !str_contains(strtolower($b->name), 'cave'));
                $assignedBiomes = $grassy->pluck('id')->toArray();
            }

            if (!empty($assignedBiomes)) {
                // Attach biomes without detaching existing ones, or just sync. We sync.
                $mob->biomes()->sync($assignedBiomes);
            }
        }
    }
}
