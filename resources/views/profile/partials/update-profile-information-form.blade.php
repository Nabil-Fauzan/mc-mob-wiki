<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data" x-data="{ imageUrl: '{{ $user->avatar_url }}', publicSlug: '{{ old('public_slug', $user->public_slug) }}', profilePublic: {{ old('profile_is_public', $user->profile_is_public) ? 'true' : 'false' }} }">
        @csrf
        @method('patch')

        <div class="p-6 bg-gray-900/50 border border-brand-500/20 rounded-2xl space-y-6 mb-6">
            <h3 class="text-md font-bold text-brand-400 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Identity & Avatar Customization
            </h3>
            
            <div>
                <x-input-label for="avatar" :value="__('Avatar (Local Upload)')" />
                
                <div class="mt-3 mb-6 flex flex-col md:flex-row items-center gap-8" x-data="{ mcUser: '{{ $user->minecraft_username }}' }">
                    <!-- Local Avatar -->
                    <div x-show="imageUrl" class="relative group">
                        <img :src="imageUrl" alt="Profile Avatar Preview" class="w-32 h-32 rounded-2xl border-2 border-brand-500/50 object-cover shadow-[0_0_20px_rgba(14,165,233,0.4)] transition-all duration-500 group-hover:scale-105">
                        <p class="absolute -bottom-2 left-1/2 -translate-x-1/2 px-2 py-0.5 bg-brand-600 text-[8px] font-black uppercase rounded text-white opacity-0 group-hover:opacity-100 transition-opacity">Local Asset</p>
                    </div>

                    <!-- 3D Body Render (Mojang API) -->
                    <template x-if="mcUser && !imageUrl">
                        <div class="relative group">
                            <div class="w-32 h-48 bg-black/40 rounded-2xl border border-white/10 flex items-center justify-center overflow-hidden shadow-2xl backdrop-blur-sm">
                                <img :src="'https://mc-heads.net/body/' + mcUser" class="h-40 group-hover:scale-110 transition-transform duration-700" alt="3D Minecraft Skin">
                            </div>
                            <p class="absolute -bottom-2 left-1/2 -translate-x-1/2 px-2 py-0.5 bg-brand-600 text-[8px] font-black uppercase rounded text-white">Aether Link Active</p>
                        </div>
                    </template>
                </div>

                <input id="avatar" name="avatar" type="file"
                    @change="
                        const file = $event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (e) => { imageUrl = e.target.result; };
                            reader.readAsDataURL(file);
                        }
                    "
                    class="mt-2 block w-full text-sm text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-brand-500/10 file:text-brand-400 hover:file:bg-brand-500/20 transition-all cursor-pointer" />
                <p class="mt-2 text-[10px] text-gray-500 font-bold uppercase tracking-widest">Accepted formats: JPG, PNG, GIF, WEBP • Max size: 2MB.</p>
                <label class="mt-3 inline-flex items-center gap-2 text-xs text-gray-300">
                    <input type="checkbox" name="remove_avatar" value="1" class="rounded border-white/10 bg-gray-950 text-red-500 focus:ring-red-500">
                    Remove local avatar and use dynamic/default identity
                </label>
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>

            <div class="pt-4 border-t border-white/5">
                <x-input-label for="minecraft_username" :value="__('Minecraft Username (Dynamic Link)')" />
                <x-text-input id="minecraft_username" name="minecraft_username" type="text" class="mt-2 block w-full bg-gray-950 border-gray-800 text-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-xl" :value="old('minecraft_username', $user->minecraft_username)" autocomplete="off" placeholder="e.g. Notch" />
                <p class="mt-2 text-[10px] text-gray-500 font-bold uppercase tracking-widest">Leave local upload empty to use the dynamic 3D head from Minotar API.</p>
                <x-input-error class="mt-2" :messages="$errors->get('minecraft_username')" />
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-950 border-gray-800 text-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-xl" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="p-6 bg-gray-900/50 border border-brand-500/10 rounded-2xl space-y-5">
            <div>
                <h3 class="text-md font-bold text-brand-400 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Public Researcher Identity
                </h3>
                <p class="mt-2 text-[11px] text-gray-500 uppercase tracking-widest">Control how your public researcher page is shared.</p>
            </div>

            <div>
                <x-input-label for="public_slug" :value="__('Public Profile Slug')" />
                <x-text-input id="public_slug" name="public_slug" type="text" x-model="publicSlug" class="mt-2 block w-full bg-gray-950 border-gray-800 text-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-xl" :value="old('public_slug', $user->public_slug)" autocomplete="off" placeholder="e.g. mob-hunter-arya" />
                <p class="mt-2 text-[10px] text-gray-500 font-bold uppercase tracking-widest">Use lowercase letters, numbers, and hyphens only.</p>
                <p class="mt-2 text-xs text-brand-300 break-all">Preview: {{ url('/researchers') }}/<span x-text="publicSlug || 'auto-generated-slug'"></span></p>
                <x-input-error class="mt-2" :messages="$errors->get('public_slug')" />
            </div>

            <label class="flex items-start justify-between gap-4 p-4 rounded-2xl border border-white/5 bg-black/20">
                <div>
                    <span class="block text-sm font-bold text-white">Public Profile Visibility</span>
                    <span class="mt-1 block text-xs text-gray-500">Allow other researchers to open and share your profile page.</span>
                </div>
                <input type="hidden" name="profile_is_public" value="0">
                <input id="profile_is_public" name="profile_is_public" type="checkbox" value="1" x-model="profilePublic" @checked(old('profile_is_public', $user->profile_is_public)) class="mt-1 rounded border-white/10 bg-gray-950 text-brand-500 focus:ring-brand-500" />
            </label>
            <p x-show="!profilePublic" class="text-[11px] text-yellow-400">Profile visibility is OFF. Your public researcher page will not be discoverable by other users.</p>

            @if($user->public_slug)
                <div class="p-4 rounded-2xl border border-white/5 bg-black/20">
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.2em] mb-2">Public Link</p>
                    <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                        <a href="{{ route('researchers.show', $user->public_slug) }}" target="_blank" class="text-sm text-brand-400 hover:text-brand-300 break-all">
                            {{ route('researchers.show', $user->public_slug) }}
                        </a>
                        <button
                            type="button"
                            onclick="navigator.clipboard.writeText('{{ route('researchers.show', $user->public_slug) }}'); window.notify('Public profile link copied', 'success');"
                            class="px-4 py-2 bg-white/5 hover:bg-white/10 text-white text-xs font-black uppercase tracking-widest rounded-xl border border-white/10 transition-all"
                        >
                            Copy Link
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    class="text-sm text-gray-600 dark:text-gray-400"
                    x-init="setTimeout(() => show = false, 2000); if (window.notify) window.notify('Profile updated successfully', 'success');"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
