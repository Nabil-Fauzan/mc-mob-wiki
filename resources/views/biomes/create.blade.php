<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 relative">
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-gray-950"></div>
            <div class="absolute w-full h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-900/40 via-gray-900/90 to-black"></div>
        </div>

        <div class="max-w-4xl mx-auto relative z-10">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('biomes.index') }}" class="inline-flex items-center text-indigo-400 hover:text-indigo-300 font-bold tracking-widest uppercase text-[10px] mb-6 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Return to Explorer
                </a>
                <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-pink-500 tracking-tight flex items-center">
                    <svg class="w-8 h-8 mr-3 text-red-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Deploy Ecosystem
                </h1>
                <p class="text-gray-400 mt-2 tracking-wide">Register a newly discovered biome into the master intelligence registry.</p>
            </div>

            <!-- Form -->
            <div class="glass-card rounded-[2.5rem] border border-white/10 p-8 shadow-[0_0_50px_rgba(0,0,0,0.5)] bg-gray-900/40 backdrop-blur-xl">
                <form action="{{ route('admin.biomes.store') }}" method="POST" enctype="multipart/form-data" 
                    class="space-y-8" 
                    x-data="biomeForm({{ $selectedParent ? $selectedParent->id : 'null' }})">
                    @csrf

                    <!-- Parent Biome Selector -->
                    <div class="bg-indigo-500/5 border border-indigo-500/20 rounded-2xl p-5">
                        <label class="block text-xs font-black uppercase tracking-widest text-indigo-400 mb-3">
                            <svg class="w-4 h-4 inline mr-1.5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                            Type
                        </label>
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <div class="relative">
                                    <input type="radio" name="_biome_type" value="top" class="sr-only" x-model="biomeType" x-on:change="parentId = null">
                                    <div class="w-5 h-5 rounded-full border-2 border-white/20 group-hover:border-indigo-400 transition-colors" x-bind:class="biomeType === 'top' ? 'border-indigo-500 bg-indigo-500/30' : ''"></div>
                                    <div class="absolute inset-0 flex items-center justify-center" x-show="biomeType === 'top'">
                                        <div class="w-2 h-2 rounded-full bg-indigo-400"></div>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-gray-300">Top-level Biome</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <div class="relative">
                                    <input type="radio" name="_biome_type" value="sub" class="sr-only" x-model="biomeType">
                                    <div class="w-5 h-5 rounded-full border-2 border-white/20 group-hover:border-indigo-400 transition-colors" x-bind:class="biomeType === 'sub' ? 'border-indigo-500 bg-indigo-500/30' : ''"></div>
                                    <div class="absolute inset-0 flex items-center justify-center" x-show="biomeType === 'sub'">
                                        <div class="w-2 h-2 rounded-full bg-indigo-400"></div>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-gray-300">Sub-biome (Variant)</span>
                            </label>
                        </div>

                        <!-- Parent selector (shown only when sub-biome is chosen) -->
                        <div x-show="biomeType === 'sub'" x-transition class="mt-4">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Parent Biome</label>
                            <select name="parent_id" x-model="parentId" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500 transition-all appearance-none">
                                <option value="" class="bg-gray-900">Select Parent Biome</option>
                                @foreach($parentBiomes as $pb)
                                    <option value="{{ $pb->id }}" class="bg-gray-900">{{ $pb->name }} ({{ $pb->dimension->name }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-xs font-black uppercase tracking-widest text-indigo-400 mb-2">Biome Name</label>
                                <input type="text" name="name" id="name" required value="{{ old('name') }}" 
                                    class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                    placeholder="e.g. Cherry Grove">
                                @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Dimension (only for top-level biomes) -->
                            <div x-show="biomeType === 'top'" x-transition>
                                <label for="dimension_id" class="block text-xs font-black uppercase tracking-widest text-indigo-400 mb-2">Dimension</label>
                                <div class="relative">
                                    <select name="dimension_id" id="dimension_id" 
                                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none cursor-pointer">
                                        <option value="" disabled selected class="bg-gray-900">Select Dimension Boundary</option>
                                        @foreach($dimensions as $dimension)
                                            <option value="{{ $dimension->id }}" class="bg-gray-900">{{ $dimension->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                @error('dimension_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Image Preview & Upload -->
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-indigo-400 mb-2">Visual Feed (Image)</label>
                            <label for="image" class="block relative group h-[200px] rounded-2xl overflow-hidden border-2 border-dashed border-white/20 bg-black/40 hover:bg-black/60 hover:border-indigo-500/50 transition-all cursor-pointer">
                                <template x-if="imageUrl">
                                    <img x-bind:src="imageUrl" class="absolute inset-0 w-full h-full object-cover z-10" alt="Preview">
                                </template>
                                <div class="absolute inset-0 flex flex-col items-center justify-center z-20" x-bind:class="imageUrl ? 'opacity-0 group-hover:opacity-100 transition-opacity' : ''">
                                    <div class="w-12 h-12 rounded-full bg-indigo-500/20 flex items-center justify-center mx-auto mb-3 text-indigo-400 group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-300 text-center block px-4" x-text="imageUrl ? 'Click to Change Image' : 'Click to Select Image'"></span>
                                </div>
                                <input type="file" name="image" id="image" accept="image/*" class="sr-only"
                                    x-on:change="
                                        if ($event.target.files.length) {
                                            let reader = new FileReader();
                                            reader.onload = e => imageUrl = e.target.result;
                                            reader.readAsDataURL($event.target.files[0]);
                                        }
                                    ">
                            </label>
                            @error('image') <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="pt-4">
                        <label for="description" class="block text-xs font-black uppercase tracking-widest text-indigo-400 mb-2">Ecosystem Profile</label>
                        <textarea name="description" id="description" rows="5" required 
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all overflow-hidden resize-none"
                            placeholder="Detailed topographic and environmental analysis...">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Action Banner -->
                    <div class="pt-6 border-t border-white/10 flex justify-end items-center gap-4">
                        <a href="{{ route('biomes.index') }}" class="text-gray-400 hover:text-white font-bold text-sm transition-colors">Abort Execution</a>
                        <button type="submit" class="relative group cursor-pointer">
                            <div class="absolute -inset-1 bg-gradient-to-r from-red-600 to-indigo-600 rounded-xl blur opacity-25 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                            <div class="relative px-8 py-4 bg-gray-900 border border-white/10 rounded-xl leading-none flex items-center">
                                <span class="pl-2 flex-1 font-black tracking-widest uppercase text-white group-hover:text-red-400 transition-colors">Finalize Deployment</span>
                                <svg class="w-5 h-5 ml-4 text-gray-400 group-hover:text-red-400 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function biomeForm(initialParentId) {
            return {
                biomeType: initialParentId ? 'sub' : 'top',
                parentId: initialParentId,
                imageUrl: '',
            }
        }
    </script>
</x-app-layout>
