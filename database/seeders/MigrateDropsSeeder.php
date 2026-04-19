<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mob;
use App\Models\MobDrop;

class MigrateDropsSeeder extends Seeder
{
    public function run()
    {
        $mobs = Mob::all();

        foreach ($mobs as $mob) {
            if ($mob->drops) {
                // Split by comma or semicolon
                $items = preg_split('/[,;]/', $mob->drops);
                
                foreach ($items as $item) {
                    $item = trim($item);
                    if (!$item) continue;

                    // Basic parsing for quantity if in parentheses like "Rotten Flesh (0-2)"
                    $quantity = '1';
                    $itemName = $item;
                    if (preg_match('/(.+)\((.+)\)/', $item, $matches)) {
                        $itemName = trim($matches[1]);
                        $quantity = trim($matches[2]);
                    }

                    // Check if already exists to avoid duplicates if run twice
                    if (!$mob->loot()->where('item_name', $itemName)->exists()) {
                        $mob->loot()->create([
                            'item_name' => $itemName,
                            'quantity' => $quantity,
                            'chance' => '100%',
                            'rarity' => 'Common',
                            'icon' => '📦'
                        ]);
                    }
                }
            }
        }
    }
}
