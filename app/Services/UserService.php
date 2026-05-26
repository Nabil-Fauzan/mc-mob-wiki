<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserService
{
    /**
     * Update user profile including file uploads, titles, and pinned mobs.
     */
    public function updateProfile(User $user, array $validatedData, Request $request)
    {
        $this->verifyTitle($user, $validatedData);

        $user->fill($validatedData);
        $user->profile_is_public = $request->boolean('profile_is_public');

        if (blank($user->public_slug)) {
            $user->public_slug = User::generateUniqueSlug($user->name, $user->id);
        }

        if ($user->isDirty('name') && blank($request->input('public_slug'))) {
            $user->public_slug = User::generateUniqueSlug($user->name, $user->id);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $this->handleAvatar($user, $request);
        $this->handleBanner($user, $request);

        $user->save();

        $this->handlePinnedMobs($user, $request);
        
        return $user;
    }

    private function verifyTitle(User $user, array $validatedData)
    {
        if (isset($validatedData['active_title'])) {
            $requestedTitle = $validatedData['active_title'];
            $unlockedTitles = $user->achievements()
                ->whereNotNull('reward_title')
                ->pluck('reward_title')
                ->toArray();
            
            if ($user->is_admin) {
                $unlockedTitles[] = 'ADMIN';
            }

            if (!empty($requestedTitle) && !in_array($requestedTitle, $unlockedTitles)) {
                abort(403, 'ILLEGAL OPERATION DETECTED: You have not unlocked the title "' . $requestedTitle . '". This incident has been logged by the Aether Protocol.');
            }
        }
    }

    private function handleAvatar(User $user, Request $request)
    {
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->boolean('remove_avatar') && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
        }
    }

    private function handleBanner(User $user, Request $request)
    {
        if ($request->hasFile('banner')) {
            if ($user->banner && !filter_var($user->banner, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->banner);
            }
            $user->banner = $request->file('banner')->store('banners', 'public');
        } elseif ($request->filled('banner_url')) {
            if ($user->banner && !filter_var($user->banner, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->banner);
            }
            $user->banner = $request->input('banner_url');
        }

        if ($request->boolean('remove_banner') && $user->banner) {
            if (!filter_var($user->banner, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->banner);
            }
            $user->banner = null;
        }
    }

    private function handlePinnedMobs(User $user, Request $request)
    {
        if ($request->has('pinned_mobs')) {
            $syncData = [];
            foreach ((array) $request->input('pinned_mobs') as $index => $mobId) {
                if ($mobId) {
                    $syncData[$mobId] = ['slot_index' => $index];
                }
            }
            $user->pinned_mobs()->sync($syncData);
        } else {
            $user->pinned_mobs()->detach();
        }
    }
}
