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

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="p-6 bg-gray-900/50 border border-indigo-500/20 rounded-2xl space-y-6 mb-6">
            <h3 class="text-md font-bold text-indigo-400 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Identity & Avatar Customization
            </h3>
            
            <div>
                <x-input-label for="avatar" :value="__('Avatar (Local Upload)')" />
                @if ($user->avatar_url)
                    <div class="mt-3 mb-4">
                        <img src="{{ $user->avatar_url }}" alt="Profile Avatar" class="w-24 h-24 rounded-2xl border-2 border-indigo-500/50 object-cover shadow-[0_0_15px_rgba(79,70,229,0.3)]">
                    </div>
                @endif
                <input id="avatar" name="avatar" type="file" class="mt-2 block w-full text-sm text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 transition-all cursor-pointer" />
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>

            <div class="pt-4 border-t border-white/5">
                <x-input-label for="minecraft_username" :value="__('Minecraft Username (Dynamic Link)')" />
                <x-text-input id="minecraft_username" name="minecraft_username" type="text" class="mt-2 block w-full bg-gray-950 border-gray-800 text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl" :value="old('minecraft_username', $user->minecraft_username)" autocomplete="off" placeholder="e.g. Notch" />
                <p class="mt-2 text-[10px] text-gray-500 font-bold uppercase tracking-widest">Leave local upload empty to use the dynamic 3D head from Minotar API.</p>
                <x-input-error class="mt-2" :messages="$errors->get('minecraft_username')" />
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-950 border-gray-800 text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
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

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
