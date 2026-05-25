<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function impersonate(User $user)
    {
        // Only admins can impersonate
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        // Don't impersonate another admin
        if ($user->is_admin) {
            return redirect()->back()->with('error', 'Cannot impersonate another admin.');
        }

        // Store original admin ID in session
        session()->put('impersonate', Auth::id());

        // Login as the target user
        Auth::loginUsingId($user->id);

        return redirect('/')->with('success', 'You are now impersonating ' . $user->name);
    }

    public function leave()
    {
        // Check if we are currently impersonating
        if (!session()->has('impersonate')) {
            return redirect('/');
        }

        $adminId = session()->pull('impersonate');

        // Login back as admin
        Auth::loginUsingId($adminId);

        return redirect('/admin')->with('success', 'You have left impersonation mode.');
    }
}
