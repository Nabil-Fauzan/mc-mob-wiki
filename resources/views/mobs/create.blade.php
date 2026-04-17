<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Minecraft Mob') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('mobs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Mob Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Category -->
                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                <x-text-input id="health" name="health" type="text" class="mt-1 block w-full" :value="old('health')" placeholder="e.g. 20 (10 hearts)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('health')" />
                            </div>

                            <!-- Damage -->
                            <div>
                                <x-input-label for="damage" :value="__('Damage Output')" />
                                <x-text-input id="damage" name="damage" type="text" class="mt-1 block w-full" :value="old('damage')" placeholder="e.g. 3 (Easy), 5 (Normal)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('damage')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Drops -->
                            <div>
                                <x-input-label for="drops" :value="__('Drops')" />
                                <x-text-input id="drops" name="drops" type="text" class="mt-1 block w-full" :value="old('drops')" placeholder="e.g. Rotten Flesh, Iron Ingot" required />
                                <x-input-error class="mt-2" :messages="$errors->get('drops')" />
                            </div>

                            <!-- Biome -->
                            <div>
                                <x-input-label for="biome_id" :value="__('Natural Habitat')" />
                                <select id="biome_id" name="biome_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="" disabled selected>Select Region</option>
                                    @php
                                        $groupedBiomes = $biomes->groupBy('dimension.name');
                                    @endphp
                                    @foreach($groupedBiomes as $dimensionName => $biomesInDim)
                                        <optgroup label="{{ $dimensionName }}">
                                            @foreach($biomesInDim as $biome)
                                                <option value="{{ $biome->id }}" {{ old('biome_id') == $biome->id ? 'selected' : '' }}>
                                                    {{ $biome->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('biome_id')" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Behavior / Description')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Image -->
                        <div x-data="{ imageUrl: null }">
                            <x-input-label for="image" :value="__('Mob Image')" />
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
                                    <p class="mt-1 text-xs text-gray-500">Field intelligence suggests JPG, PNG, or GIF up to 2MB.</p>
                                </div>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('mobs.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
                            <x-primary-button>
                                {{ __('Save Mob') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
