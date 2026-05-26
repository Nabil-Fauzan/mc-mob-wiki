<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SaveMobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'        => 'required',
            'category_id' => 'required|exists:categories,id',
            'biome_ids'   => 'nullable|array',
            'biome_ids.*' => 'exists:biomes,id',
            'health'      => 'nullable',
            'damage'      => 'nullable',
            'drops'       => 'nullable|string',
            'xp_reward'   => 'nullable|string',
            'description' => 'required',
            'spawning_conditions' => 'nullable|string',
            'health_easy'   => 'nullable|string',
            'health_normal' => 'nullable|string',
            'health_hard'   => 'nullable|string',
            'damage_easy'   => 'nullable|string',
            'damage_normal' => 'nullable|string',
            'damage_hard'   => 'nullable|string',
            'melee_attack'  => 'nullable|string',
            'ranged_attack' => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'loot'          => 'nullable|array',
            'loot.*.item_name' => 'required|string',
            'loot.*.quantity'  => 'nullable|string',
            'loot.*.chance'    => 'nullable|string',
            'loot.*.rarity'    => 'nullable|string',
            'loot.*.icon'      => 'nullable|string',
        ];
    }
}
