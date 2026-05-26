<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SaveBiomeRequest extends FormRequest
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
        $isSubBiome = !empty($this->parent_id);
        
        return [
            'name'        => 'required|string|max:255',
            'parent_id'   => 'nullable|exists:biomes,id',
            'dimension_id'=> $isSubBiome ? 'nullable' : 'required|exists:dimensions,id',
            'description' => 'required|string',
            'image'       => 'nullable|image|max:2048',
            'existing_image' => 'nullable|string',
        ];
    }
}
