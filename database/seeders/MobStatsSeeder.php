<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mob;

class MobStatsSeeder extends Seeder
{
    public function run(): void
    {
        $stats = [
            'Warden' => ['hp' => 500, 'dmg' => [16, 30, 45]],
            'Ender Dragon' => ['hp' => 200, 'dmg' => [6, 10, 15]],
            'Wither' => ['hp' => 300, 'dmg' => [5, 8, 12]],
            'Elder Guardian' => ['hp' => 80, 'dmg' => [5, 8, 12]],
            'Iron Golem' => ['hp' => 100, 'dmg' => [4, 11, 21]],
            'Ravager' => ['hp' => 100, 'dmg' => [7, 12, 18]],
            'Enderman' => ['hp' => 40, 'dmg' => [4.5, 7, 10.5]],
            'Piglin Brute' => ['hp' => 50, 'dmg' => [7, 13, 19]],
            'Zoglin' => ['hp' => 40, 'dmg' => [3, 5, 8]],
            'Hoglin' => ['hp' => 40, 'dmg' => [2.5, 3, 4.5]],
            'Guardian' => ['hp' => 30, 'dmg' => [4, 6, 9]],
            'Evoker' => ['hp' => 24, 'dmg' => [6, 6, 6]],
            'Vindicator' => ['hp' => 24, 'dmg' => [7, 13, 19]],
            'Illusioner' => ['hp' => 32, 'dmg' => [5, 5, 5]],
            'Spider' => ['hp' => 16, 'dmg' => [2, 2, 3]],
            'Cave Spider' => ['hp' => 12, 'dmg' => [2, 2, 3]],
            'Creeper' => ['hp' => 20, 'dmg' => [22.5, 43, 64]],
            'Zombie' => ['hp' => 20, 'dmg' => [2.5, 3, 4.5]],
            'Husk' => ['hp' => 20, 'dmg' => [2.5, 3, 4.5]],
            'Drowned' => ['hp' => 20, 'dmg' => [3, 3, 4]],
            'Skeleton' => ['hp' => 20, 'dmg' => [3, 4, 5]],
            'Stray' => ['hp' => 20, 'dmg' => [3, 4, 5]],
            'Wither Skeleton' => ['hp' => 20, 'dmg' => [5, 8, 12]],
            'Phantom' => ['hp' => 20, 'dmg' => [4, 6, 9]],
            'Blaze' => ['hp' => 20, 'dmg' => [4, 5, 7]],
            'Ghast' => ['hp' => 10, 'dmg' => [7, 12, 17]],
            'Magma Cube' => ['hp' => 16, 'dmg' => [3, 4, 6]], // large
            'Slime' => ['hp' => 16, 'dmg' => [3, 4, 6]], // large
            'Shulker' => ['hp' => 30, 'dmg' => [4, 4, 4]],
            'Witch' => ['hp' => 26, 'dmg' => [6, 6, 6]], // potions
            'Silverfish' => ['hp' => 8, 'dmg' => [1, 1, 1.5]],
            'Endermite' => ['hp' => 8, 'dmg' => [2, 2, 3]],
            'Vex' => ['hp' => 14, 'dmg' => [3, 5, 7]],
            'Piglin' => ['hp' => 16, 'dmg' => [3, 5, 7]],
            'Zombified Piglin' => ['hp' => 20, 'dmg' => [5, 5, 7]],
            
            // Passives & Neutral default rules if not in this list
        ];

        foreach (Mob::all() as $mob) {
            $baseHp = 20;
            $dmgE = 0; $dmgN = 0; $dmgH = 0;

            if (isset($stats[$mob->name])) {
                $baseHp = $stats[$mob->name]['hp'];
                $dmgE = $stats[$mob->name]['dmg'][0];
                $dmgN = $stats[$mob->name]['dmg'][1];
                $dmgH = $stats[$mob->name]['dmg'][2];
            } else {
                // Apply sensible defaults based on category if not explicitly listed
                $cat = $mob->category->name ?? 'Neutral';
                if ($cat == 'Hostile') {
                    $baseHp = 20;
                    $dmgE = 2; $dmgN = 3; $dmgH = 4;
                } elseif ($cat == 'Passive' || in_array($mob->name, ['Cow', 'Pig', 'Sheep', 'Chicken', 'Mooshroom', 'Villager', 'Allay', 'Bat', 'Cat', 'Fox', 'Frog', 'Ocelot', 'Parrot', 'Rabbit', 'Salmon', 'Snow Golem', 'Squid', 'Strider', 'Tadpole', 'Turtle'])) {
                    if (in_array($mob->name, ['Cow', 'Pig'])) { $baseHp = 10; }
                    elseif (in_array($mob->name, ['Sheep'])) { $baseHp = 8; }
                    elseif (in_array($mob->name, ['Chicken', 'Parrot', 'Salmon', 'Bat'])) { $baseHp = 4; }
                    else { $baseHp = 10; }
                    $dmgE = 0; $dmgN = 0; $dmgH = 0;
                } elseif ($cat == 'Neutral') {
                    // Wolf, Llama, Goat, Bee, Panda, Polar Bear
                    if ($mob->name == 'Wolf') { $baseHp = 8; $dmgE = 3; $dmgN = 4; $dmgH = 6; }
                    elseif ($mob->name == 'Goat') { $baseHp = 10; $dmgE = 1; $dmgN = 2; $dmgH = 3; }
                    elseif ($mob->name == 'Llama' || $mob->name == 'Trader Llama') { $baseHp = 15; $dmgE = 1; $dmgN = 1; $dmgH = 1; }
                    elseif ($mob->name == 'Polar Bear') { $baseHp = 30; $dmgE = 4; $dmgN = 6; $dmgH = 9; }
                    elseif ($mob->name == 'Panda') { $baseHp = 20; $dmgE = 4; $dmgN = 6; $dmgH = 9; }
                    elseif ($mob->name == 'Bee') { $baseHp = 10; $dmgE = 2; $dmgN = 2; $dmgH = 3; }
                    else { $baseHp = 20; $dmgE = 2; $dmgN = 3; $dmgH = 4; }
                }
            }

            // The user requested to set health per difficulty as well, although HP doesn't change by difficulty in MC.
            // We will set them identical to satisfy the UI fields.
            $mob->update([
                'health' => $baseHp,
                'health_easy' => $baseHp,
                'health_normal' => $baseHp,
                'health_hard' => $baseHp,
                'damage' => $dmgN, // fallback base
                'damage_easy' => $dmgE,
                'damage_normal' => $dmgN,
                'damage_hard' => $dmgH,
            ]);
        }
    }
}
