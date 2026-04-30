<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 relative">
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-gray-950"></div>
            <div class="absolute w-full h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-brand-900/40 via-gray-900/90 to-black"></div>
        </div>

        <div class="max-w-4xl mx-auto relative z-10">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('biomes.show', $biome) }}" class="inline-flex items-center text-brand-400 hover:text-brand-300 font-bold tracking-widest uppercase text-[10px] mb-6 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Return to Detail View
                </a>
                <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-pink-500 tracking-tight flex items-center">
                    <svg class="w-8 h-8 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Reconfigure Ecosystem
                </h1>
                <p class="text-gray-400 mt-2 tracking-wide">Modifying existing intel for <span class="text-white font-bold">{{ $biome->name }}</span>.</p>
            </div>

            <!-- Form -->
            <div class="glass-card rounded-[2.5rem] border border-white/10 p-8 shadow-[0_0_50px_rgba(0,0,0,0.5)] bg-gray-900/40 backdrop-blur-xl">
                <form action="{{ route('admin.biomes.update', $biome) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-8"
                    x-data="biomeEditForm('{{ $biome->parent_id ? 'sub' : 'top' }}', {{ $biome->parent_id ?? 'null' }}, '{{ $biome->image ? asset('storage/' . $biome->image) : '' }}', {{ $presetImages->toJson() }}, {{ $uploadedImages->toJson() }})">
                    @csrf
                    @method('PUT')

                    <!-- Type Toggle -->
                    <div class="bg-brand-500/5 border border-brand-500/20 rounded-2xl p-5">
                        <label class="block text-xs font-black uppercase tracking-widest text-brand-400 mb-3">
                            <svg class="w-4 h-4 inline mr-1.5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                            Type
                        </label>
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <div class="relative w-5 h-5">
                                    <input type="radio" name="_biome_type" value="top" class="sr-only" x-model="biomeType">
                                    <div class="w-5 h-5 rounded-full border-2 border-white/20 group-hover:border-brand-400 transition-colors" x-bind:class="biomeType === 'top' ? 'border-brand-500 bg-brand-500/30' : ''"></div>
                                    <div class="absolute inset-0 flex items-center justify-center" x-show="biomeType === 'top'">
                                        <div class="w-2 h-2 rounded-full bg-brand-400"></div>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-gray-300">Top-level Biome</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <div class="relative w-5 h-5">
                                    <input type="radio" name="_biome_type" value="sub" class="sr-only" x-model="biomeType">
                                    <div class="w-5 h-5 rounded-full border-2 border-white/20 group-hover:border-brand-400 transition-colors" x-bind:class="biomeType === 'sub' ? 'border-brand-500 bg-brand-500/30' : ''"></div>
                                    <div class="absolute inset-0 flex items-center justify-center" x-show="biomeType === 'sub'">
                                        <div class="w-2 h-2 rounded-full bg-brand-400"></div>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-gray-300">Sub-biome (Variant)</span>
                            </label>
                        </div>

                        <!-- Parent dropdown for sub-biome -->
                        <div x-show="biomeType === 'sub'" x-transition class="mt-4">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Parent Biome</label>
                            <select name="parent_id" x-model="parentId" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-brand-500 transition-all appearance-none">
                                <option value="" class="bg-gray-900">Select Parent Biome</option>
                                @foreach($parentBiomes as $pb)
                                    <option value="{{ $pb->id }}" {{ $biome->parent_id == $pb->id ? 'selected' : '' }} class="bg-gray-900">
                                        {{ $pb->name }} ({{ $pb->dimension->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-xs font-black uppercase tracking-widest text-brand-400 mb-2">Biome Name</label>
                                <input type="text" name="name" id="name" required value="{{ old('name', $biome->name) }}"
                                    class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
                                @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Dimension (only for top-level) -->
                            <div x-show="biomeType === 'top'" x-transition>
                                <label for="dimension_id" class="block text-xs font-black uppercase tracking-widest text-brand-400 mb-2">Dimension</label>
                                <div class="relative">
                                    <select name="dimension_id" id="dimension_id"
                                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all appearance-none cursor-pointer">
                                        @foreach($dimensions as $dimension)
                                            <option value="{{ $dimension->id }}" {{ (old('dimension_id', $biome->dimension_id) == $dimension->id) ? 'selected' : '' }} class="bg-gray-900">
                                                {{ $dimension->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                @error('dimension_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="relative">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-xs font-black uppercase tracking-widest text-brand-400">Visual Feed (Image)</label>
                                <button type="button" @click="showSelector = !showSelector" class="text-[10px] font-black uppercase tracking-widest text-brand-500 hover:text-brand-400 transition-colors flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                    Quick Select
                                </button>
                            </div>

                            <!-- Hidden input for existing image -->
                            <input type="hidden" name="existing_image" x-model="selectedPath">

                            <div class="relative group">
                                <label for="image" class="block relative h-[200px] rounded-2xl overflow-hidden border-2 border-dashed border-white/20 bg-black/40 hover:bg-black/60 hover:border-brand-500/50 transition-all cursor-pointer">
                                    <template x-if="imageUrl">
                                        <img x-bind:src="imageUrl" class="absolute inset-0 w-full h-full object-cover z-10" alt="Preview">
                                    </template>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center z-20" x-bind:class="imageUrl ? 'opacity-0 group-hover:opacity-100 transition-opacity' : ''">
                                        <div class="w-12 h-12 rounded-full bg-brand-500/20 flex items-center justify-center mx-auto mb-3 text-brand-400 group-hover:bg-brand-500 group-hover:text-white transition-colors">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </div>
                                        <span class="text-sm font-bold text-gray-300 text-center block px-4"
                                            x-text="imageUrl ? 'Click to Change Image' : 'Click to Select Image'"></span>
                                    </div>
                                    <input type="file" name="image" id="image" accept="image/*" class="sr-only" x-ref="fileInput"
                                        x-on:change="
                                            if ($event.target.files.length) {
                                                selectedPath = '';
                                                let reader = new FileReader();
                                                reader.onload = e => imageUrl = e.target.result;
                                                reader.readAsDataURL($event.target.files[0]);
                                            }
                                        ">
                                </label>

                                <!-- Dropdown Selector -->
                                <div x-show="showSelector" 
                                    @click.away="showSelector = false"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="absolute top-full left-0 right-0 mt-2 z-50 bg-gray-900 border border-white/10 rounded-2xl shadow-2xl overflow-hidden backdrop-blur-xl">
                                    
                                    <div class="p-3 border-b border-white/10">
                                        <input type="text" x-model="searchQuery" placeholder="Search images..." class="w-full bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:ring-1 focus:ring-brand-500 outline-none">
                                    </div>

                                    <div class="max-h-[300px] overflow-y-auto custom-scrollbar">
                                        <!-- Presets -->
                                        <template x-if="filteredPresets.length > 0">
                                            <div class="p-2">
                                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-2 px-2">Preset Ecosystems</p>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <template x-for="path in filteredPresets" :key="path">
                                                        <button type="button" @click="selectImage(path, 'preset')" class="group relative aspect-video rounded-lg overflow-hidden border border-white/5 hover:border-brand-500/50 transition-all">
                                                            <img :src="'{{ asset('') }}' + path" class="w-full h-full object-cover">
                                                            <div class="absolute inset-0 bg-black/40 group-hover:bg-black/10 transition-colors"></div>
                                                            <p class="absolute bottom-1 left-2 right-2 text-[8px] text-white truncate font-bold" x-text="(path && typeof path === 'string') ? path.split('/').pop() : ''"></p>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Previous Uploads -->
                                        <template x-if="filteredUploads.length > 0">
                                            <div class="p-2 border-t border-white/5">
                                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-2 px-2">Previous Discoveries</p>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <template x-for="path in filteredUploads" :key="path">
                                                        <button type="button" @click="selectImage(path, 'upload')" class="group relative aspect-video rounded-lg overflow-hidden border border-white/5 hover:border-brand-500/50 transition-all">
                                                            <img :src="'{{ asset('storage') }}' + '/' + path" class="w-full h-full object-cover">
                                                            <div class="absolute inset-0 bg-black/40 group-hover:bg-black/10 transition-colors"></div>
                                                            <p class="absolute bottom-1 left-2 right-2 text-[8px] text-white truncate font-bold" x-text="(path && typeof path === 'string') ? path.split('/').pop() : ''"></p>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        <template x-if="filteredPresets.length === 0 && filteredUploads.length === 0">
                                            <div class="p-8 text-center">
                                                <p class="text-xs text-gray-500 uppercase tracking-widest font-black">No matches found</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            @error('image') <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
                            <p class="text-[10px] text-gray-500 mt-2 uppercase tracking-widest text-center">Leave blank to keep current image</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="pt-4">
                        <label for="description" class="block text-xs font-black uppercase tracking-widest text-brand-400 mb-2">Ecosystem Profile</label>
                        <textarea name="description" id="description" rows="5" required
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all overflow-hidden resize-none">{{ old('description', $biome->description) }}</textarea>
                        @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Actions -->
                    <div class="pt-6 border-t border-white/10 flex justify-between items-center">
                        {{-- Trigger delete form outside --}}
                        <button type="button" onclick="if(confirm('WARNING: Erase this ecosystem permanently?')) document.getElementById('delete-biome-form').submit();" 
                            class="inline-flex items-center space-x-2 text-red-600 hover:text-red-400 font-bold text-xs uppercase tracking-widest transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span>Eradicate</span>
                        </button>

                        <div class="flex items-center gap-4">
                            <a href="{{ route('biomes.show', $biome) }}" class="text-gray-400 hover:text-white font-bold text-sm transition-colors">Cancel</a>
                            <button type="submit" class="relative group cursor-pointer">
                                <div class="absolute -inset-1 bg-gradient-to-r from-red-600 to-brand-600 rounded-xl blur opacity-25 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                                <div class="relative px-8 py-4 bg-gray-900 border border-white/10 rounded-xl leading-none flex items-center">
                                    <span class="pl-2 flex-1 font-black tracking-widest uppercase text-white group-hover:text-red-400 transition-colors">Commit Overwrite</span>
                                    <svg class="w-5 h-5 ml-4 text-gray-400 group-hover:text-red-400 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </div>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Form Outside Main Form -->
    <form id="delete-biome-form" action="{{ route('admin.biomes.destroy', $biome) }}" method="POST" style="display:none">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function biomeEditForm(initialType, initialParentId, initialImageUrl, presets, uploads) {
            return {
                biomeType: initialType,
                parentId: initialParentId,
                imageUrl: initialImageUrl || '',
                showSelector: false,
                selectedPath: '',
                searchQuery: '',
                presets: presets,
                uploads: uploads,
                get filteredPresets() {
                    return this.presets.filter(p => p.toLowerCase().includes(this.searchQuery.toLowerCase())).slice(0, 10);
                },
                get filteredUploads() {
                    return this.uploads.filter(u => u.toLowerCase().includes(this.searchQuery.toLowerCase())).slice(0, 10);
                },
                selectImage(path, type) {
                    this.selectedPath = path;
                    if (type === 'preset') {
                        this.imageUrl = '{{ asset('') }}' + path;
                    } else {
                        this.imageUrl = '{{ asset('storage') }}' + '/' + path;
                    }
                    this.showSelector = false;
                    this.$refs.fileInput.value = '';
                }
            }
        }
    </script>
</x-app-layout>
