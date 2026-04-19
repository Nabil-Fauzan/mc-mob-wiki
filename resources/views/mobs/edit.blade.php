<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Mob: ') . $mob->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('mobs.update', $mob) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Mob Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $mob->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Category -->
                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $mob->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Health -->
                            <div>
                                <x-input-label for="health" :value="__('Health Points')" />
                                <x-text-input id="health" name="health" type="text" class="mt-1 block w-full" :value="old('health', $mob->health)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('health')" />
                            </div>

                            <!-- Damage -->
                            <div>
                                <x-input-label for="damage" :value="__('Damage Output')" />
                                <x-text-input id="damage" name="damage" type="text" class="mt-1 block w-full" :value="old('damage', $mob->damage)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('damage')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Drops -->
                            <div>
                                <x-input-label for="drops" :value="__('Drops')" />
                                <x-text-input id="drops" name="drops" type="text" class="mt-1 block w-full" :value="old('drops', $mob->drops)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('drops')" />
                            </div>

                            <!-- Biomes -->
                            <div class="col-span-full">
                                <x-input-label :value="__('Natural Habitats (Select many)')" />
                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4 bg-gray-900/50 rounded-xl border border-white/5 max-h-[400px] overflow-y-auto custom-scrollbar">
                                    @php
                                        $groupedBiomes = $biomes->groupBy('dimension.name');
                                        $selectedBiomes = old('biome_ids', $mob->biomes->pluck('id')->toArray());
                                    @endphp
                                    @foreach($groupedBiomes as $dimensionName => $biomesInDim)
                                        <div class="space-y-3">
                                            <h4 class="text-[10px] font-black uppercase tracking-widest text-indigo-400 border-b border-indigo-500/20 pb-1 mb-2">{{ $dimensionName }}</h4>
                                            @foreach($biomesInDim->whereNull('parent_id') as $parentBiome)
                                                <div class="space-y-2">
                                                    <label class="flex items-center group cursor-pointer">
                                                        <div class="relative flex items-center">
                                                            <input type="checkbox" name="biome_ids[]" value="{{ $parentBiome->id }}" 
                                                                class="w-4 h-4 rounded border-white/10 bg-black/40 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-gray-900 transition-all cursor-pointer"
                                                                {{ in_array($parentBiome->id, $selectedBiomes) ? 'checked' : '' }}>
                                                        </div>
                                                        <span class="ml-2 text-xs font-bold text-gray-300 group-hover:text-white transition-colors">{{ $parentBiome->name }}</span>
                                                    </label>
                                                    
                                                    @foreach($biomesInDim->where('parent_id', $parentBiome->id) as $subBiome)
                                                        <label class="flex items-center group cursor-pointer ml-4">
                                                            <div class="relative flex items-center">
                                                                <input type="checkbox" name="biome_ids[]" value="{{ $subBiome->id }}" 
                                                                    class="w-3.5 h-3.5 rounded border-white/10 bg-black/40 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-gray-900 transition-all cursor-pointer"
                                                                    {{ in_array($subBiome->id, $selectedBiomes) ? 'checked' : '' }}>
                                                            </div>
                                                            <span class="ml-2 text-[11px] font-medium text-gray-400 group-hover:text-indigo-300 transition-colors">↳ {{ $subBiome->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('biome_ids')" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Behavior / Description')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('description', $mob->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Spawning Conditions -->
                        <div>
                            <x-input-label for="spawning_conditions" :value="__('Special Spawning Conditions (Optional)')" />
                            <textarea id="spawning_conditions" name="spawning_conditions" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="e.g. Spawned via Ender Pearl, Village Mechanics, etc.">{{ old('spawning_conditions', $mob->spawning_conditions) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500 italic">Use this for mobs that don't spawn naturally in biomes.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('spawning_conditions')" />
                        </div>

                        <!-- Image -->
                        <div x-data="{ imageUrl: '{{ $mob->image ? asset('storage/' . $mob->image) : null }}' }">
                            <x-input-label for="image" :value="__('Change Mob Image (Optional)')" />
                            <div class="mt-2 mb-4 flex items-center space-x-6">
                                <template x-if="imageUrl">
                                    <div class="relative w-32 h-32 rounded-2xl overflow-hidden border border-white/10 bg-gray-900 shadow-xl">
                                        <img :src="imageUrl" class="w-full h-full object-contain p-2">
                                    </div>
                                </template>
                                <template x-if="!imageUrl">
                                    <div class="w-32 h-32 rounded-2xl border-2 border-dashed border-white/10 flex items-center justify-center text-gray-700 bg-white/5">
                                        <svg class="w-8 h-8 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                </template>
                                <div class="flex-1">
                                    <input id="image" name="image" type="file" 
                                        @change="imageUrl = URL.createObjectURL($event.target.files[0])"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300" accept="image/*" />
                                    <p class="mt-1 text-xs text-gray-500">Field intelligence suggests JPG, PNG, or GIF up to 2MB. Leave blank to keep current image.</p>
                                </div>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('mobs.show', $mob) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
                            <x-primary-button>
                                {{ __('Update Mob') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
