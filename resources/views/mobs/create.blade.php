<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Minecraft Mob') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        name: '',
        category_id: '',
        h_easy: '', h_normal: '', h_hard: '',
        d_easy: '', d_normal: '', d_hard: '',
        xp: '',
        desc: '',
        applyTemplate(type) {
            if(type === 'boss') {
                this.category_id = '1';
                this.h_easy = '100 (50 hearts)'; this.h_normal = '200 (100 hearts)'; this.h_hard = '300 (150 hearts)';
                this.d_easy = '10 (5 hearts)'; this.d_normal = '15 (7.5 hearts)'; this.d_hard = '25 (12.5 hearts)';
                this.xp = '500';
                this.desc = 'A formidable dimensional guardian with immense vitality and devastating combat capabilities.';
            } else if(type === 'hostile') {
                this.category_id = '1';
                this.h_easy = '16 (8 hearts)'; this.h_normal = '20 (10 hearts)'; this.h_hard = '30 (15 hearts)';
                this.d_easy = '2 (1 heart)'; this.d_normal = '3 (1.5 hearts)'; this.d_hard = '5 (2.5 hearts)';
                this.xp = '5';
                this.desc = 'An aggressive entity that engages researchers on sight. Standard combat protocols advised.';
            } else if(type === 'passive') {
                this.category_id = '2';
                this.h_easy = '10 (5 hearts)'; this.h_normal = '10 (5 hearts)'; this.h_hard = '10 (5 hearts)';
                this.d_easy = '0'; this.d_normal = '0'; this.d_hard = '0';
                this.xp = '1-3';
                this.desc = 'A non-threatening biological entity. Typically flees when approached or damaged.';
            }
        }
    }">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Template Bar -->
            <div class="mb-8 p-4 bg-brand-500/5 border border-brand-500/20 rounded-3xl flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-brand-500/20 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-xs font-black text-brand-400 uppercase tracking-widest">Smart Templates</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="applyTemplate('boss')" class="px-4 py-1.5 bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 rounded-xl text-[10px] font-black text-red-500 uppercase tracking-tighter transition-all">Boss Entity</button>
                    <button type="button" @click="applyTemplate('hostile')" class="px-4 py-1.5 bg-orange-500/10 hover:bg-orange-500/20 border border-orange-500/20 rounded-xl text-[10px] font-black text-orange-500 uppercase tracking-tighter transition-all">Hostile Mob</button>
                    <button type="button" @click="applyTemplate('passive')" class="px-4 py-1.5 bg-green-500/10 hover:bg-green-500/20 border border-green-500/20 rounded-xl text-[10px] font-black text-green-500 uppercase tracking-tighter transition-all">Passive Fauna</button>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('mobs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Mob Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" x-model="name" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Category -->
                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" x-model="category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-brand-500 dark:focus:border-brand-600 focus:ring-brand-500 dark:focus:ring-brand-600 rounded-md shadow-sm" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <!-- Vitality -->
                        <div class="col-span-full space-y-4">
                            <h4 class="text-xs font-black text-red-500 uppercase tracking-[0.2em] flex items-center">
                                Vitality Registry (Health)
                                <span class="flex-1 h-px bg-red-500/10 ml-4"></span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="health_easy" :value="__('Easy HP')" />
                                    <x-text-input id="health_easy" name="health_easy" type="text" x-model="h_easy" class="mt-1 block w-full border-green-500/30" placeholder="e.g. 20 (10 hearts)" />
                                </div>
                                <div>
                                    <x-input-label for="health_normal" :value="__('Normal HP')" />
                                    <x-text-input id="health_normal" name="health_normal" type="text" x-model="h_normal" class="mt-1 block w-full border-yellow-500/30" placeholder="e.g. 20 (10 hearts)" />
                                </div>
                                <div>
                                    <x-input-label for="health_hard" :value="__('Hard HP')" />
                                    <x-text-input id="health_hard" name="health_hard" type="text" x-model="h_hard" class="mt-1 block w-full border-red-500/30" placeholder="e.g. 20 (10 hearts)" />
                                </div>
                            </div>
                        </div>

                        <!-- Combat -->
                        <div class="col-span-full space-y-4">
                            <h4 class="text-xs font-black text-orange-500 uppercase tracking-[0.2em] flex items-center">
                                Damage Intelligence (Attack Power)
                                <span class="flex-1 h-px bg-orange-500/10 ml-4"></span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="damage_easy" :value="__('Easy Damage')" />
                                    <x-text-input id="damage_easy" name="damage_easy" type="text" x-model="d_easy" class="mt-1 block w-full border-green-500/30" placeholder="e.g. 3 (1.5 hearts)" />
                                </div>
                                <div>
                                    <x-input-label for="damage_normal" :value="__('Normal Damage')" />
                                    <x-text-input id="damage_normal" name="damage_normal" type="text" x-model="d_normal" class="mt-1 block w-full border-yellow-500/30" placeholder="e.g. 5 (2.5 hearts)" />
                                </div>
                                <div>
                                    <x-input-label for="damage_hard" :value="__('Hard Damage')" />
                                    <x-text-input id="damage_hard" name="damage_hard" type="text" x-model="d_hard" class="mt-1 block w-full border-red-500/30" placeholder="e.g. 8 (4 hearts)" />
                                </div>
                            </div>
                        </div>

                        <!-- Loot & Experience Intelligence -->
                        <div class="col-span-full space-y-6">
                            <h4 class="text-xs font-black text-yellow-500 uppercase tracking-[0.2em] flex items-center">
                                Loot Intelligence & Experience
                                <span class="flex-1 h-px bg-yellow-500/10 ml-4"></span>
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- XP Reward -->
                                <div>
                                    <x-input-label for="xp_reward" :value="__('Experience (XP) Reward')" />
                                    <x-text-input id="xp_reward" name="xp_reward" type="text" x-model="xp" class="mt-1 block w-full border-green-500/20" placeholder="e.g. 5, 1-3, or 50 (Boss)" />
                                    <p class="mt-1 text-[10px] text-gray-500">Points awarded upon entity neutralisation</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('xp_reward')" />
                                </div>

                                <!-- Legacy Quick Drops (Hidden/Internal) -->
                                <div>
                                    <x-input-label for="drops" :value="__('Legacy Quick Add (Optional)')" />
                                    <x-text-input id="drops" name="drops" type="text" class="mt-1 block w-full opacity-60" :value="old('drops')" placeholder="e.g. Rotten Flesh, Iron Ingot" />
                                    <p class="mt-1 text-[10px] text-gray-500">Quick comma-separated list for indexing</p>
                                </div>
                            </div>

                            <!-- Dynamic Loot Manager -->
                            <div x-data="{ 
                                items: @json(old('loot', [])),
                                addItem() {
                                    this.items.push({ item_name: '', quantity: '1', chance: '100%', rarity: 'Common', icon: '📦' });
                                },
                                removeItem(index) {
                                    this.items.splice(index, 1);
                                }
                            }" class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Structured Loot Table</span>
                                    <button type="button" @click="addItem()" class="px-3 py-1 bg-yellow-500/10 hover:bg-yellow-500/20 border border-yellow-500/20 rounded-lg text-[10px] font-black text-yellow-500 uppercase transition-all flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                        Add Item
                                    </button>
                                </div>

                                <div class="space-y-3">
                                    <template x-for="(item, index) in items" :key="index">
                                        <div class="glass-card p-4 rounded-2xl border-white/5 grid grid-cols-1 md:grid-cols-12 gap-4 items-end relative group loot-pop">
                                            <div class="md:col-span-1">
                                                <x-input-label :value="__('Icon')" class="text-[9px] text-center" />
                                                <input type="text" :name="'loot['+index+'][icon]'" x-model="item.icon" class="mt-1 block w-full bg-black/40 border-white/10 rounded-lg text-sm text-center px-0 text-white focus:ring-yellow-500/50" placeholder="📦">
                                            </div>
                                            <div class="md:col-span-4">
                                                <x-input-label :value="__('Item Name')" class="text-[9px]" />
                                                <input type="text" :name="'loot['+index+'][item_name]'" x-model="item.item_name" class="mt-1 block w-full bg-black/40 border-white/10 rounded-lg text-sm text-white focus:ring-yellow-500/50" required placeholder="e.g. Iron Ingot">
                                            </div>
                                            <div class="md:col-span-2">
                                                <x-input-label :value="__('Qty')" class="text-[9px]" />
                                                <input type="text" :name="'loot['+index+'][quantity]'" x-model="item.quantity" class="mt-1 block w-full bg-black/40 border-white/10 rounded-lg text-sm text-white focus:ring-yellow-500/50" placeholder="e.g. 1-3">
                                            </div>
                                            <div class="md:col-span-2">
                                                <x-input-label :value="__('Chance')" class="text-[9px]" />
                                                <input type="text" :name="'loot['+index+'][chance]'" x-model="item.chance" class="mt-1 block w-full bg-black/40 border-white/10 rounded-lg text-sm text-white focus:ring-yellow-500/50" placeholder="e.g. 50%">
                                            </div>
                                            <div class="md:col-span-2">
                                                <x-input-label :value="__('Rarity')" class="text-[9px]" />
                                                <select :name="'loot['+index+'][rarity]'" x-model="item.rarity" class="mt-1 block w-full bg-black/40 border-white/10 rounded-lg text-sm text-white focus:ring-yellow-500/50">
                                                    <option value="Common">Common</option>
                                                    <option value="Uncommon">Uncommon</option>
                                                    <option value="Rare">Rare</option>
                                                    <option value="Legendary">Legendary</option>
                                                </select>
                                            </div>
                                            <div class="md:col-span-1 flex justify-center pb-1">
                                                <button type="button" @click="removeItem(index)" class="p-2 text-red-500/50 hover:text-red-500 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </template>

                                    <div x-show="items.length === 0" class="py-10 text-center glass-card rounded-2xl border-dashed border-white/5">
                                        <p class="text-xs text-gray-600 italic">No structured loot defined for this entity.</p>
                                        <button type="button" @click="addItem()" class="mt-4 text-[10px] font-black text-yellow-500 uppercase underline tracking-widest">Initialize Table</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <!-- Biomes -->
                            <div class="col-span-full">
                                <x-input-label :value="__('Natural Habitats (Select many)')" />
                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4 bg-gray-900/50 rounded-xl border border-white/5 max-h-[400px] overflow-y-auto custom-scrollbar">
                                    @php
                                        $groupedBiomes = $biomes->groupBy('dimension.name');
                                    @endphp
                                    @foreach($groupedBiomes as $dimensionName => $biomesInDim)
                                        <div class="space-y-3">
                                            <h4 class="text-[10px] font-black uppercase tracking-widest text-brand-400 border-b border-brand-500/20 pb-1 mb-2">{{ $dimensionName }}</h4>
                                            @foreach($biomesInDim->whereNull('parent_id') as $parentBiome)
                                                <div class="space-y-2">
                                                    <label class="flex items-center group cursor-pointer">
                                                        <div class="relative flex items-center">
                                                            <input type="checkbox" name="biome_ids[]" value="{{ $parentBiome->id }}" 
                                                                class="w-4 h-4 rounded border-white/10 bg-black/40 text-brand-600 focus:ring-brand-500 focus:ring-offset-gray-900 transition-all cursor-pointer"
                                                                {{ is_array(old('biome_ids')) && in_array($parentBiome->id, old('biome_ids')) ? 'checked' : '' }}>
                                                        </div>
                                                        <span class="ml-2 text-xs font-bold text-gray-300 group-hover:text-white transition-colors">{{ $parentBiome->name }}</span>
                                                    </label>
                                                    
                                                    @foreach($biomesInDim->where('parent_id', $parentBiome->id) as $subBiome)
                                                        <label class="flex items-center group cursor-pointer ml-4">
                                                            <div class="relative flex items-center">
                                                                <input type="checkbox" name="biome_ids[]" value="{{ $subBiome->id }}" 
                                                                    class="w-3.5 h-3.5 rounded border-white/10 bg-black/40 text-brand-500 focus:ring-brand-500 focus:ring-offset-gray-900 transition-all cursor-pointer"
                                                                    {{ is_array(old('biome_ids')) && in_array($subBiome->id, old('biome_ids')) ? 'checked' : '' }}>
                                                            </div>
                                                            <span class="ml-2 text-[11px] font-medium text-gray-400 group-hover:text-brand-300 transition-colors">↳ {{ $subBiome->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('biome_ids')" />
                            </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Behavior / Description')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-brand-500 dark:focus:border-brand-600 focus:ring-brand-500 dark:focus:ring-brand-600 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Spawning Conditions -->
                        <div>
                            <x-input-label for="spawning_conditions" :value="__('Special Spawning Conditions (Optional)')" />
                            <textarea id="spawning_conditions" name="spawning_conditions" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-brand-500 dark:focus:border-brand-600 focus:ring-brand-500 dark:focus:ring-brand-600 rounded-md shadow-sm" placeholder="e.g. Spawned via Ender Pearl, Village Mechanics, etc.">{{ old('spawning_conditions') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500 italic">Use this for mobs that don't spawn naturally in biomes.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('spawning_conditions')" />
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
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-gray-700 dark:file:text-gray-300" accept="image/*" />
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
