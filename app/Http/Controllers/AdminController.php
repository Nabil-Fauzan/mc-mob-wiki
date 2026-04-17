<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mob;
use App\Models\Comment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_mobs' => Mob::count(),
            'total_comments' => Comment::count(),
            'new_users' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];
        
        $recentUsers = User::latest()->limit(5)->get();
        $recentComments = Comment::with(['user', 'mob'])->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentComments'));
    }
}
