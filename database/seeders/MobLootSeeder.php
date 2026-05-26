<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mob;
use App\Models\MobDrop;
use Illuminate\Support\Facades\DB;

class MobLootSeeder extends Seeder
{
    public function run(): void
    {
        $mobsData = [
            'Zombie' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Rotten Flesh', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Iron Ingot', 'quantity' => '1', 'chance' => '2.5%', 'rarity' => 'Rare'],
                    ['item_name' => 'Carrot', 'quantity' => '1', 'chance' => '2.5%', 'rarity' => 'Uncommon'],
                    ['item_name' => 'Potato', 'quantity' => '1', 'chance' => '2.5%', 'rarity' => 'Uncommon'],
                ]
            ],
            'Skeleton' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Bone', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Arrow', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Bow', 'quantity' => '1', 'chance' => '8.5%', 'rarity' => 'Rare'],
                ]
            ],
            'Creeper' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Gunpowder', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Spider' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'String', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Spider Eye', 'quantity' => '0-1', 'chance' => '33%', 'rarity' => 'Uncommon'],
                ]
            ],
            'Enderman' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Ender Pearl', 'quantity' => '0-1', 'chance' => '50%', 'rarity' => 'Uncommon'],
                ]
            ],
            'Wither' => [
                'xp' => 50,
                'drops' => [
                    ['item_name' => 'Nether Star', 'quantity' => '1', 'chance' => '100%', 'rarity' => 'Legendary'],
                ]
            ],
            'Ender Dragon' => [
                'xp' => 12000,
                'drops' => [
                    ['item_name' => 'Dragon Egg', 'quantity' => '1', 'chance' => '100%', 'rarity' => 'Legendary'],
                ]
            ],
            'Cow' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Raw Beef', 'quantity' => '1-3', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Leather', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Pig' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Raw Porkchop', 'quantity' => '1-3', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Sheep' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Raw Mutton', 'quantity' => '1-2', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Wool', 'quantity' => '1', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Chicken' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Raw Chicken', 'quantity' => '1', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Feather', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Iron Golem' => [
                'xp' => 0,
                'drops' => [
                    ['item_name' => 'Iron Ingot', 'quantity' => '3-5', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Poppy', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Uncommon'],
                ]
            ],
            'Snow Golem' => [
                'xp' => 0,
                'drops' => [
                    ['item_name' => 'Snowball', 'quantity' => '0-15', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Witch' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Glass Bottle', 'quantity' => '0-2', 'chance' => '25%', 'rarity' => 'Uncommon'],
                    ['item_name' => 'Glowstone Dust', 'quantity' => '0-2', 'chance' => '25%', 'rarity' => 'Uncommon'],
                    ['item_name' => 'Gunpowder', 'quantity' => '0-2', 'chance' => '25%', 'rarity' => 'Uncommon'],
                    ['item_name' => 'Redstone Dust', 'quantity' => '0-2', 'chance' => '25%', 'rarity' => 'Uncommon'],
                    ['item_name' => 'Spider Eye', 'quantity' => '0-2', 'chance' => '25%', 'rarity' => 'Uncommon'],
                    ['item_name' => 'Sugar', 'quantity' => '0-2', 'chance' => '25%', 'rarity' => 'Uncommon'],
                    ['item_name' => 'Stick', 'quantity' => '0-2', 'chance' => '25%', 'rarity' => 'Uncommon'],
                ]
            ],
            'Slime' => [
                'xp' => 3,
                'drops' => [
                    ['item_name' => 'Slimeball', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Magma Cube' => [
                'xp' => 3,
                'drops' => [
                    ['item_name' => 'Magma Cream', 'quantity' => '0-1', 'chance' => '100%', 'rarity' => 'Uncommon'],
                ]
            ],
            'Blaze' => [
                'xp' => 10,
                'drops' => [
                    ['item_name' => 'Blaze Rod', 'quantity' => '0-1', 'chance' => '50%', 'rarity' => 'Uncommon'],
                ]
            ],
            'Ghast' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Ghast Tear', 'quantity' => '0-1', 'chance' => '100%', 'rarity' => 'Rare'],
                    ['item_name' => 'Gunpowder', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Zombieified Piglin' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Rotten Flesh', 'quantity' => '0-1', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Gold Nugget', 'quantity' => '0-1', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Gold Ingot', 'quantity' => '1', 'chance' => '2.5%', 'rarity' => 'Rare'],
                    ['item_name' => 'Golden Sword', 'quantity' => '1', 'chance' => '8.5%', 'rarity' => 'Rare'],
                ]
            ],
            'Phantom' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Phantom Membrane', 'quantity' => '0-1', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Drowned' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Rotten Flesh', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Copper Ingot', 'quantity' => '1', 'chance' => '11%', 'rarity' => 'Uncommon'],
                    ['item_name' => 'Nautilus Shell', 'quantity' => '1', 'chance' => '3%', 'rarity' => 'Rare'],
                    ['item_name' => 'Trident', 'quantity' => '1', 'chance' => '6.25%', 'rarity' => 'Legendary'],
                ]
            ],
            'Husk' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Rotten Flesh', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Iron Ingot', 'quantity' => '1', 'chance' => '2.5%', 'rarity' => 'Rare'],
                    ['item_name' => 'Carrot', 'quantity' => '1', 'chance' => '2.5%', 'rarity' => 'Uncommon'],
                    ['item_name' => 'Potato', 'quantity' => '1', 'chance' => '2.5%', 'rarity' => 'Uncommon'],
                ]
            ],
            'Stray' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Bone', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Arrow', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Arrow of Slowness', 'quantity' => '0-1', 'chance' => '50%', 'rarity' => 'Uncommon'],
                ]
            ],
            'Cave Spider' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'String', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Spider Eye', 'quantity' => '0-1', 'chance' => '33%', 'rarity' => 'Uncommon'],
                ]
            ],
            'Silverfish' => [
                'xp' => 5,
                'drops' => []
            ],
            'Endermite' => [
                'xp' => 3,
                'drops' => []
            ],
            'Guardian' => [
                'xp' => 10,
                'drops' => [
                    ['item_name' => 'Prismarine Shard', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Raw Cod', 'quantity' => '1', 'chance' => '50%', 'rarity' => 'Common'],
                    ['item_name' => 'Prismarine Crystals', 'quantity' => '1', 'chance' => '40%', 'rarity' => 'Uncommon'],
                ]
            ],
            'Elder Guardian' => [
                'xp' => 50,
                'drops' => [
                    ['item_name' => 'Prismarine Shard', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Raw Cod', 'quantity' => '1', 'chance' => '50%', 'rarity' => 'Common'],
                    ['item_name' => 'Prismarine Crystals', 'quantity' => '1', 'chance' => '40%', 'rarity' => 'Uncommon'],
                    ['item_name' => 'Wet Sponge', 'quantity' => '1', 'chance' => '100%', 'rarity' => 'Rare'],
                ]
            ],
            'Shulker' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Shulker Shell', 'quantity' => '0-1', 'chance' => '50%', 'rarity' => 'Rare'],
                ]
            ],
            'Evoker' => [
                'xp' => 10,
                'drops' => [
                    ['item_name' => 'Totem of Undying', 'quantity' => '1', 'chance' => '100%', 'rarity' => 'Legendary'],
                    ['item_name' => 'Emerald', 'quantity' => '0-1', 'chance' => '100%', 'rarity' => 'Uncommon'],
                ]
            ],
            'Vindicator' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Emerald', 'quantity' => '0-1', 'chance' => '100%', 'rarity' => 'Uncommon'],
                    ['item_name' => 'Iron Axe', 'quantity' => '1', 'chance' => '8.5%', 'rarity' => 'Rare'],
                ]
            ],
            'Pillager' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Arrow', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Crossbow', 'quantity' => '1', 'chance' => '8.5%', 'rarity' => 'Rare'],
                    ['item_name' => 'Ominous Banner', 'quantity' => '1', 'chance' => '100%', 'rarity' => 'Legendary'], // Drops if leader
                ]
            ],
            'Ravager' => [
                'xp' => 20,
                'drops' => [
                    ['item_name' => 'Saddle', 'quantity' => '1', 'chance' => '100%', 'rarity' => 'Rare'],
                ]
            ],
            'Piglin' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Golden Sword', 'quantity' => '1', 'chance' => '8.5%', 'rarity' => 'Uncommon'],
                    ['item_name' => 'Crossbow', 'quantity' => '1', 'chance' => '8.5%', 'rarity' => 'Uncommon'],
                ]
            ],
            'Piglin Brute' => [
                'xp' => 20,
                'drops' => [
                    ['item_name' => 'Golden Axe', 'quantity' => '1', 'chance' => '8.5%', 'rarity' => 'Rare'],
                ]
            ],
            'Hoglin' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Raw Porkchop', 'quantity' => '2-4', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Leather', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Zoglin' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Rotten Flesh', 'quantity' => '1-3', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Warden' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Sculk Catalyst', 'quantity' => '1', 'chance' => '100%', 'rarity' => 'Legendary'],
                ]
            ],
            'Axolotl' => [
                'xp' => 1,
                'drops' => []
            ],
            'Bee' => [
                'xp' => 1,
                'drops' => []
            ],
            'Camel' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Saddle', 'quantity' => '0-1', 'chance' => '100%', 'rarity' => 'Rare'],
                ]
            ],
            'Cat' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'String', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Donkey' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Leather', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Fox' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Rabbit\'s Foot', 'quantity' => '0-1', 'chance' => '8.5%', 'rarity' => 'Rare'],
                ]
            ],
            'Frog' => [
                'xp' => 1,
                'drops' => []
            ],
            'Giant' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Rotten Flesh', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Goat' => [
                'xp' => 1,
                'drops' => []
            ],
            'Horse' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Leather', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Illusioner' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Bow', 'quantity' => '1', 'chance' => '8.5%', 'rarity' => 'Rare'],
                ]
            ],
            'Llama' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Leather', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Mooshroom' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Raw Beef', 'quantity' => '1-3', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Leather', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Mule' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Leather', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Ocelot' => [
                'xp' => 1,
                'drops' => []
            ],
            'Panda' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Bamboo', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Parrot' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Feather', 'quantity' => '1-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Polar Bear' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Raw Cod', 'quantity' => '0-2', 'chance' => '75%', 'rarity' => 'Common'],
                    ['item_name' => 'Raw Salmon', 'quantity' => '0-2', 'chance' => '25%', 'rarity' => 'Uncommon'],
                ]
            ],
            'Rabbit' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Rabbit Hide', 'quantity' => '0-1', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Raw Rabbit', 'quantity' => '0-1', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Rabbit\'s Foot', 'quantity' => '1', 'chance' => '10%', 'rarity' => 'Rare'],
                ]
            ],
            'Wither Skeleton' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Coal', 'quantity' => '0-1', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Bone', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Wither Skeleton Skull', 'quantity' => '1', 'chance' => '2.5%', 'rarity' => 'Legendary'],
                    ['item_name' => 'Stone Sword', 'quantity' => '1', 'chance' => '8.5%', 'rarity' => 'Rare'],
                ]
            ],
            'Zombified Piglin' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Rotten Flesh', 'quantity' => '0-1', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Gold Nugget', 'quantity' => '0-1', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Gold Ingot', 'quantity' => '1', 'chance' => '2.5%', 'rarity' => 'Rare'],
                    ['item_name' => 'Golden Sword', 'quantity' => '1', 'chance' => '8.5%', 'rarity' => 'Rare'],
                ]
            ],
            'Allay' => [
                'xp' => 0,
                'drops' => []
            ],
            'Skeleton Horse' => [
                'xp' => 1,
                'drops' => []
            ],
            'Sniffer' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Torchflower Seeds', 'quantity' => '1', 'chance' => '50%', 'rarity' => 'Rare'],
                    ['item_name' => 'Pitcher Pod', 'quantity' => '1', 'chance' => '50%', 'rarity' => 'Rare'],
                ]
            ],
            'Strider' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'String', 'quantity' => '2-5', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Saddle', 'quantity' => '0-1', 'chance' => '100%', 'rarity' => 'Rare'],
                ]
            ],
            'Trader Llama' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Leather', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Lead', 'quantity' => '1', 'chance' => '100%', 'rarity' => 'Uncommon'],
                ]
            ],
            'Turtle' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Scute', 'quantity' => '1', 'chance' => '100%', 'rarity' => 'Rare'],
                    ['item_name' => 'Seagrass', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Vex' => [
                'xp' => 3,
                'drops' => []
            ],
            'Wolf' => [
                'xp' => 1,
                'drops' => []
            ],
            'Zombie Horse' => [
                'xp' => 1,
                'drops' => [
                    ['item_name' => 'Rotten Flesh', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                ]
            ],
            'Zombie Villager' => [
                'xp' => 5,
                'drops' => [
                    ['item_name' => 'Rotten Flesh', 'quantity' => '0-2', 'chance' => '100%', 'rarity' => 'Common'],
                    ['item_name' => 'Iron Ingot', 'quantity' => '1', 'chance' => '2.5%', 'rarity' => 'Rare'],
                    ['item_name' => 'Carrot', 'quantity' => '1', 'chance' => '2.5%', 'rarity' => 'Uncommon'],
                    ['item_name' => 'Potato', 'quantity' => '1', 'chance' => '2.5%', 'rarity' => 'Uncommon'],
                ]
            ],
            'Villager' => [
                'xp' => 0,
                'drops' => []
            ]
        ];

        DB::beginTransaction();

        try {
            foreach ($mobsData as $mobName => $data) {
                // Find mob (case-insensitive for generic matches)
                $mob = Mob::whereRaw('LOWER(name) = ?', [strtolower($mobName)])->first();

                if ($mob) {
                    $mob->update(['xp_reward' => $data['xp']]);
                    
                    // Clear existing drops for a fresh seed
                    $mob->loot()->delete();

                    foreach ($data['drops'] as $drop) {
                        $mob->loot()->create($drop);
                    }
                }
            }
            DB::commit();
            $this->command->info('Mob Loot and EXP seeded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding loot: ' . $e->getMessage());
        }
    }
}
