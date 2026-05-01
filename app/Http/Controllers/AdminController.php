<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Mob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        $recentComments = Comment::with(['user', 'mob'])->latest()->limit(10)->get();
        $recentMobs = Mob::with('category')->latest()->limit(12)->get();

        $diagnostics = [
            'uncategorized_mobs' => Mob::whereNull('category_id')->count(),
            'mobs_without_biomes' => Mob::doesntHave('biomes')->count(),
            'mobs_without_images' => Mob::whereNull('image')->count(),
            'comments_last_24h' => Comment::where('created_at', '>=', now()->subDay())->count(),
            'cache_driver' => config('cache.default'),
            'cache_health' => Cache::store()->getStore() ? 'OK' : 'WARN',
        ];

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentComments', 'recentMobs', 'diagnostics'));
    }

    public function moderateComment(Comment $comment)
    {
        $payload = [
            'comment_id' => $comment->id,
            'mob_id' => $comment->mob_id,
            'author_id' => $comment->user_id,
            'admin_id' => auth()->id(),
            'action' => 'delete_comment',
            'at' => now()->toIso8601String(),
        ];

        $comment->delete();
        Log::channel('daily')->info('admin_audit', $payload);

        return back()->with('success', 'Comment moderated and removed.');
    }

    public function bulkDeleteMobs(Request $request)
    {
        $validated = $request->validate([
            'mob_ids' => ['required', 'array'],
            'mob_ids.*' => ['integer', 'exists:mobs,id'],
        ]);

        $ids = collect($validated['mob_ids'])->unique()->values();
        $deleted = Mob::whereIn('id', $ids)->delete();

        Log::channel('daily')->info('admin_audit', [
            'admin_id' => auth()->id(),
            'action' => 'bulk_delete_mobs',
            'mob_ids' => $ids->all(),
            'deleted_count' => $deleted,
            'at' => now()->toIso8601String(),
        ]);

        return back()->with('success', "Bulk delete completed: {$deleted} mob(s) removed.");
    }
}
