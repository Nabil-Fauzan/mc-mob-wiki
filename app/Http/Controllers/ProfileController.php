<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Get unlocked titles from achievements
        $titles = $user->achievements()
                       ->whereNotNull('reward_title')
                       ->pluck('reward_title')
                       ->toArray();

        if ($user->is_admin) {
            $titles[] = 'ADMIN';
        }

        $favoriteMobs = $user->favorite_mobs()->get();
        $pinnedMobs = $user->pinned_mobs()->pluck('mobs.id')->toArray();

        return view('profile.edit', [
            'user' => $user,
            'titles' => $titles,
            'favoriteMobs' => $favoriteMobs,
            'pinnedMobs' => $pinnedMobs,
        ]);
    }

    /**
     * Display the public profile.
     */
    public function show(string $slug): View
    {
        $user = User::where('public_slug', $slug)->firstOrFail();

        if (!$user->profile_is_public && (!Auth::check() || Auth::id() !== $user->id)) {
            abort(403, 'This profile is private.');
        }

        $pinnedMobs = $user->pinned_mobs()->with('category')->get();
        $recentComments = $user->comments()->with('mob')->latest()->take(5)->get();

        return view('profile.show', [
            'user' => $user,
            'pinnedMobs' => $pinnedMobs,
            'recentComments' => $recentComments,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());
        $user->profile_is_public = $request->boolean('profile_is_public');

        if (blank($user->public_slug)) {
            $user->public_slug = User::generateUniqueSlug($user->name, $user->id);
        }

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

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($user->isDirty('name') && blank($request->input('public_slug'))) {
            $user->public_slug = User::generateUniqueSlug($user->name, $user->id);
        }

        // Handle Banner
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

        $user->save();

        // Handle Pinned Mobs
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

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Revoke a specific session for the user.
     */
    public function revokeSession(Request $request, string $id): RedirectResponse
    {
        \Illuminate\Support\Facades\DB::table('sessions')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return Redirect::route('profile.edit')->with('status', 'session-revoked');
    }
}
