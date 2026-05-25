<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    public function toggle(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot connect to yourself.');
        }

        /** @var \App\Models\User $activeUser */
        $activeUser = Auth::user();

        if ($activeUser->isFollowing($user)) {
            $activeUser->following()->detach($user->id);
            $message = 'Connection to ' . $user->name . ' severed.';
        } else {
            $activeUser->following()->attach($user->id);
            $message = 'Connection to ' . $user->name . ' established.';
            
            $user->notify(new \App\Notifications\UserFollowed($activeUser->name, $activeUser->public_slug));
        }

        return back()->with('success', $message);
    }
}
