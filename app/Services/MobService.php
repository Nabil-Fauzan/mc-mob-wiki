<?php

namespace App\Services;

use App\Models\Mob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MobService
{
    /**
     * Store or update a mob with relationships and images.
     */
    public function saveMob(Mob $mob, Request $request): Mob
    {
        $data = $request->except(['biome_ids', 'loot']);
        
        // Sync base fields for sorting/compatibility
        $data['health'] = $request->health_normal ?: ($request->health_easy ?: ($request->health_hard ?: '0'));
        $data['damage'] = $request->damage_normal ?: ($request->damage_easy ?: ($request->damage_hard ?: '0'));

        if ($request->hasFile('image')) {
            if ($mob->exists && $mob->image) {
                Storage::disk('public')->delete($mob->image);
            }
            $data['image'] = $request->file('image')->store('mobs', 'public');
        }

        // Track revisions before saving (if it's an update)
        if ($mob->exists) {
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
        } else {
            $mob = Mob::create($data);
        }
        
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

        return $mob;
    }
}
